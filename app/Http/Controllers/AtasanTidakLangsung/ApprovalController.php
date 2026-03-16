<?php

namespace App\Http\Controllers\AtasanTidakLangsung;

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
        // Ambil employee_id dari users yang atasan tidak langsungnya adalah user yang login
        $employeeIds = Employee::where('atasan_tidak_langsung_id', Auth::id())
            ->pluck('id');

        // Ambil leave requests yang sudah disetujui atasan langsung (status menunggu atasan tidak langsung)
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType', 'approvalAtasanLangsung'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'menunggu_atasan_tidak_langsung')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.atasan_tidak_langsung.approvals.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Load relasi yang diperlukan
        $leaveRequest->load(['employee.user', 'employee.atasanTidakLangsung', 'leaveType', 'approvals.approver']);

        // Pastikan hanya atasan yang bersangkutan yang bisa melihat
        if ($leaveRequest->employee->atasan_tidak_langsung_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.atasan_tidak_langsung.approvals.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        try {
            // Validasi atasan
            if ($leaveRequest->employee->atasan_tidak_langsung_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melakukan approval ini.'
                ], 403);
            }

            // Cek apakah sudah diproses
            if ($leaveRequest->status != 'menunggu_atasan_tidak_langsung') {
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
                'level' => 'atasan_tidak_langsung'
            ],
            [
                'approver_id' => Auth::id(),
                'status' => 'disetujui',
                'note' => $request->catatan ?? 'Disetujui oleh atasan tidak langsung',
                'approved_at' => now()
            ]
            );

            // Update status leave request menjadi disetujui
            $leaveRequest->update([
                'status' => 'disetujui'
            ]);

            DB::commit();

            // ========== NOTIFIKASI WHATSAPP ==========
            // Kirim notif ke semua user kepegawaian
            $leaveRequest->load('employee.user', 'leaveType');
            $employee    = $leaveRequest->employee;
            $namePegawai = $employee->user->nama ?? '-';
            $leaveType   = $leaveRequest->leaveType->name ?? 'Cuti';
            $startDate   = \Carbon\Carbon::parse($leaveRequest->start_date)->format('d/m/Y');
            $endDate     = \Carbon\Carbon::parse($leaveRequest->end_date)->format('d/m/Y');
            $totalDays   = $leaveRequest->total_days;

            $wa = new WhatsappService();
            $kepegawaianUsers = User::where('role', 'kepegawaian')->get();
            foreach ($kepegawaianUsers as $kpg) {
                if ($kpg->whatsapp) {
                    $wa->sendMessage($kpg->whatsapp,
                        "✅ *PENGAJUAN CUTI DISETUJUI PENUH*\n\n"
                        . "Pegawai: {$namePegawai}\n"
                        . "Jenis Cuti: {$leaveType}\n"
                        . "Tanggal: {$startDate} s/d {$endDate} ({$totalDays} hari)\n\n"
                        . "Pengajuan cuti telah disetujui oleh atasan langsung dan atasan tidak langsung. Silakan login untuk mencetak surat cuti."
                    );
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
            if ($leaveRequest->employee->atasan_tidak_langsung_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melakukan approval ini.'
                ], 403);
            }

            // Cek apakah sudah diproses
            if ($leaveRequest->status != 'menunggu_atasan_tidak_langsung') {
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
                'level' => 'atasan_tidak_langsung'
            ],
            [
                'approver_id' => Auth::id(),
                'status' => $newStatus,
                'note' => $request->alasan_penolakan ?? $request->catatan,
                'approved_at' => now()
            ]
            );

            // Update status leave request
            $leaveRequest->update([
                'status' => $newStatus
            ]);

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
