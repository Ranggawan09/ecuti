<?php
// app/Http/Controllers/AtasanLangsung/ApprovalController.php

namespace App\Http\Controllers\AtasanLangsung;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Approval;
use App\Models\Employee;
use App\Models\User;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        // Ambil employee_id dari users yang atasan langsungnya adalah user yang login
        $employeeIds = Employee::where('atasan_langsung_id', Auth::id())
            ->pluck('id');

        // Ambil leave requests dari employees tersebut yang statusnya menunggu atasan langsung
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType', 'approvalAtasanLangsung'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'menunggu_atasan_langsung')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.atasan_langsung.approvals.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Load relasi yang diperlukan
        $leaveRequest->load(['employee.user', 'employee.atasanLangsung', 'leaveType', 'approvals.approver']);

        // Pastikan hanya atasan yang bersangkutan yang bisa melihat
        if ($leaveRequest->employee->atasan_langsung_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return view('pages.atasan_langsung.approvals._show_partial', compact('leaveRequest'));
        }

        return view('pages.atasan_langsung.approvals.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        try {
            // Validasi atasan
            if ($leaveRequest->employee->atasan_langsung_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melakukan approval ini.'
                ], 403);
            }

            // Cek apakah sudah diproses
            if ($leaveRequest->status != 'menunggu_atasan_langsung') {
                return response()->json([
                    'success' => false,
                    'message' => 'Permohonan cuti ini sudah diproses sebelumnya.'
                ], 400);
            }

            DB::beginTransaction();

            // Update atau create approval record
            Approval::updateOrCreate(
            [
                'leave_request_id' => $leaveRequest->id,
                'level' => 'atasan_langsung'
            ],
            [
                'approver_id' => Auth::id(),
                'status' => 'disetujui',
                'note' => $request->catatan ?? 'Disetujui oleh atasan langsung',
                'approved_at' => now()
            ]
            );

            // Update status leave request
            // Jika ada atasan tidak langsung, status jadi menunggu atasan tidak langsung
            // Jika tidak ada, langsung disetujui
            $hasAtasanTidakLangsung = $leaveRequest->employee->atasan_tidak_langsung_id != null;

            $leaveRequest->update([
                'status' => $hasAtasanTidakLangsung ? 'menunggu_atasan_tidak_langsung' : 'disetujui'
            ]);

            // Deduct leave balance if fully approved
            if (!$hasAtasanTidakLangsung) {
                $daysToDeduct = (int) $leaveRequest->total_days;
                $balances = \App\Models\LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                    ->where(function($q) {
                        $q->where('remaining_days', '>', 0)
                          ->orWhere('carried_over_days', '>', 0);
                    })
                    ->orderBy('year', 'asc')
                    ->get();
                
                foreach ($balances as $balance) {
                    if ($daysToDeduct <= 0) break;

                    if ($balance->carried_over_days > 0) {
                        $deduct = min($balance->carried_over_days, $daysToDeduct);
                        $balance->carried_over_days -= $deduct;
                        $balance->used_days += $deduct;
                        $daysToDeduct -= $deduct;
                    }

                    if ($daysToDeduct <= 0) {
                        $balance->save();
                        break;
                    }

                    if ($balance->remaining_days > 0) {
                        $deduct = min($balance->remaining_days, $daysToDeduct);
                        $balance->remaining_days -= $deduct;
                        $balance->used_days += $deduct;
                        $daysToDeduct -= $deduct;
                    }
                    
                    $balance->save();
                }
            }

            DB::commit();

            // ========== NOTIFIKASI WHATSAPP ==========
            $wa          = new WhatsappService();
            $leaveRequest->load('employee.user', 'employee.atasanTidakLangsung', 'leaveType');
            $employee    = $leaveRequest->employee;
            $namePegawai = $employee->user->nama ?? '-';
            $leaveType   = $leaveRequest->leaveType->name ?? 'Cuti';
            $startDate   = \Carbon\Carbon::parse($leaveRequest->start_date)->format('d/m/Y');
            $endDate     = \Carbon\Carbon::parse($leaveRequest->end_date)->format('d/m/Y');
            $totalDays   = $leaveRequest->total_days;

            if ($hasAtasanTidakLangsung) {
                // Notif ke atasan tidak langsung
                $atl = $employee->atasanTidakLangsung;
                if ($atl && $atl->whatsapp) {
                    $wa->sendMessage($atl->whatsapp,
                        "✅ *PENGAJUAN CUTI MENUNGGU PERSETUJUAN ANDA*\n\n"
                        . "Pegawai: {$namePegawai}\n"
                        . "Jenis Cuti: {$leaveType}\n"
                        . "Tanggal: {$startDate} s/d {$endDate} ({$totalDays} hari)\n\n"
                        . "Pengajuan ini sudah disetujui atasan langsung. Silakan login untuk memproses."
                    );
                }
            } else {
                // Tidak ada ATL → notif ke semua kepegawaian
                $kepegawaianUsers = User::where('role', 'kepegawaian')->get();
                foreach ($kepegawaianUsers as $kpg) {
                    if ($kpg->whatsapp) {
                        $wa->sendMessage($kpg->whatsapp,
                            "✅ *PENGAJUAN CUTI DISETUJUI*\n\n"
                            . "Pegawai: {$namePegawai}\n"
                            . "Jenis Cuti: {$leaveType}\n"
                            . "Tanggal: {$startDate} s/d {$endDate} ({$totalDays} hari)\n\n"
                            . "Pengajuan cuti telah disetujui sepenuhnya. Silakan login untuk mencetak surat cuti."
                        );
                    }
                }
            }
            // =========================================

            return response()->json([
                'success' => true,
                'message' => 'Permohonan cuti berhasil disetujui.',
                'data' => [
                    'status' => $leaveRequest->status
                ]
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $newStatus = $request->input('status', 'tidak_disetujui');

        // Validasi: hanya status yang diizinkan
        if (!in_array($newStatus, ['tidak_disetujui', 'perubahan', 'ditangguhkan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid.'
            ], 422);
        }

        // Alasan opsional
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500'
        ]);

        try {
            // Validasi atasan
            if ($leaveRequest->employee->atasan_langsung_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melakukan approval ini.'
                ], 403);
            }

            // Cek apakah sudah diproses
            if ($leaveRequest->status != 'menunggu_atasan_langsung') {
                return response()->json([
                    'success' => false,
                    'message' => 'Permohonan cuti ini sudah diproses sebelumnya.'
                ], 400);
            }

            DB::beginTransaction();

            // Update atau create approval record
            Approval::updateOrCreate(
            [
                'leave_request_id' => $leaveRequest->id,
                'level' => 'atasan_langsung'
            ],
            [
                'approver_id' => Auth::id(),
                'status' => $newStatus,
                'note' => $request->alasan_penolakan ?? $request->catatan,
                'approved_at' => now()
            ]
            );

            // Update status leave request
            $hasAtasanTidakLangsung = $leaveRequest->employee->atasan_tidak_langsung_id != null;
            
            if ($newStatus === 'ditangguhkan' && $leaveRequest->is_penangguhan) {
                $finalStatus = $hasAtasanTidakLangsung ? 'menunggu_atasan_tidak_langsung' : 'ditangguhkan';
                $leaveRequest->update([
                    'status' => $finalStatus
                ]);

                // Apply penangguhan logic if fully approved (no atasan tidak langsung)
                if ($finalStatus === 'ditangguhkan') {
                    $year = \Carbon\Carbon::parse($leaveRequest->start_date)->year;
                    $leaveBalance = \App\Models\LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                        ->where('year', $year)
                        ->first();
                    
                    if ($leaveBalance) {
                        $daysToCarry = min(6, (int) $leaveRequest->total_days);
                        // Deduct from remaining days of current year
                        $leaveBalance->remaining_days -= $daysToCarry;
                        // Add to carried_over_days in the same year
                        $leaveBalance->carried_over_days += $daysToCarry;
                        $leaveBalance->save();
                    }
                }
            } else {
                $leaveRequest->update([
                    'status' => $newStatus
                ]);
            }

            DB::commit();

            // ========== NOTIFIKASI WHATSAPP ==========
            // Kirim notif ke pegawai bahwa cuti ditolak/ditangguhkan
            $leaveRequest->load('employee.user', 'leaveType');
            $employee    = $leaveRequest->employee;
            $pegawaiUser = $employee->user;
            if ($pegawaiUser && $pegawaiUser->whatsapp) {
                $wa          = new WhatsappService();
                $leaveType   = $leaveRequest->leaveType->name ?? 'Cuti';
                $startDate   = \Carbon\Carbon::parse($leaveRequest->start_date)->format('d/m/Y');
                $endDate     = \Carbon\Carbon::parse($leaveRequest->end_date)->format('d/m/Y');
                $statusLabel = match ($newStatus) {
                    'tidak_disetujui' => 'Tidak Disetujui ❌',
                    'ditangguhkan'    => 'Ditangguhkan ⏸️',
                    'perubahan'       => 'Perlu Perubahan 🔄',
                    default           => $newStatus,
                };
                $catatan = $request->alasan_penolakan ?? $request->catatan ?? '-';

                $wa->sendMessage($pegawaiUser->whatsapp,
                    "❌ *STATUS PENGAJUAN CUTI DIPERBARUI*\n\n"
                    . "Jenis Cuti: {$leaveType}\n"
                    . "Tanggal: {$startDate} s/d {$endDate}\n"
                    . "Status: {$statusLabel}\n"
                    . "Catatan: {$catatan}\n\n"
                    . "Silakan login ke aplikasi untuk info lebih lanjut."
                );
            }
            // =========================================

            $messageMap = [
                'tidak_disetujui' => 'Permohonan cuti berhasil ditolak.',
                'perubahan' => 'Permohonan cuti ditandai perlu perubahan.',
                'ditangguhkan' => 'Permohonan cuti berhasil ditangguhkan.',
            ];

            return response()->json([
                'success' => true,
                'message' => $messageMap[$newStatus] ?? 'Keputusan berhasil disimpan.',
                'data' => [
                    'status' => $leaveRequest->status
                ]
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}