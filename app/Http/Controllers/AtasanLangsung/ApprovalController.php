<?php
// app/Http/Controllers/AtasanLangsung/ApprovalController.php

namespace App\Http\Controllers\AtasanLangsung;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Approval;
use App\Models\Employee;
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permohonan cuti berhasil disetujui.',
                'data' => [
                    'status' => $leaveRequest->status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:500'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan harus diisi',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter'
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
                    'status' => 'ditolak',
                    'note' => $request->alasan_penolakan,
                    'approved_at' => now()
                ]
            );

            // Update status leave request
            $leaveRequest->update([
                'status' => 'ditolak'
            ]);

            DB::commit();

            // TODO: Kirim notifikasi ke pegawai
            // $this->sendNotificationToEmployee($leaveRequest);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan cuti berhasil ditolak. Pegawai akan menerima notifikasi untuk revisi.',
                'data' => [
                    'status' => $leaveRequest->status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}