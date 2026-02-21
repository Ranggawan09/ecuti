<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Approval;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveApprovalService
{
    /**
     * Proses persetujuan cuti oleh atasan
     */
    public function approve(
        LeaveRequest $leaveRequest,
        User $approver,
        string $note = null
        ): void
    {
        DB::transaction(function () use ($leaveRequest, $approver, $note) {

            $approval = Approval::where('leave_request_id', $leaveRequest->id)
                ->where('approver_id', $approver->id)
                ->firstOrFail();

            $approval->update([
                'status' => 'disetujui',
                'note' => $note,
                'approved_at' => now(),
            ]);

            // Approval level logic
            if ($approval->level === 'atasan_langsung') {
                $leaveRequest->update([
                    'status' => 'menunggu_atasan_tidak_langsung'
                ]);

                $this->notifyNextApprover($leaveRequest);
            }

            if ($approval->level === 'atasan_tidak_langsung') {
                $leaveRequest->update([
                    'status' => 'disetujui'
                ]);

                $this->finalizeLeave($leaveRequest);
            }
        });
    }

    /**
     * Proses penolakan cuti
     */
    public function reject(
        LeaveRequest $leaveRequest,
        User $approver,
        string $note
        ): void
    {
        DB::transaction(function () use ($leaveRequest, $approver, $note) {

            Approval::where('leave_request_id', $leaveRequest->id)
                ->where('approver_id', $approver->id)
                ->update([
                'status' => 'tidak_disetujui',
                'note' => $note,
                'approved_at' => now(),
            ]);

            $leaveRequest->update([
                'status' => 'tidak_disetujui'
            ]);

            $this->notifyEmployee(
                $leaveRequest,
                'Pengajuan cuti Anda ditolak. Catatan: ' . $note
            );
        });
    }

    /**
     * Penyelesaian akhir setelah disetujui penuh
     */
    private function finalizeLeave(LeaveRequest $leaveRequest): void
    {
        $leaveType = $leaveRequest->leaveType;

        if ($leaveType->deduct_balance) {
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                ->where('year', Carbon::now()->year)
                ->lockForUpdate()
                ->firstOrFail();

            $balance->deduct($leaveRequest->total_days);
        }

        // Notifikasi ke pegawai
        $this->notifyEmployee(
            $leaveRequest,
            'Pengajuan cuti Anda telah DISETUJUI sepenuhnya.'
        );

        // Notifikasi ke kepegawaian
        $this->notifyKepegawaian($leaveRequest);
    }

    /**
     * Kirim notifikasi ke atasan berikutnya
     */
    private function notifyNextApprover(LeaveRequest $leaveRequest): void
    {
        $employee = $leaveRequest->employee;
        $nextApprover = $employee->atasanTidakLangsung;

        if ($nextApprover) {
            Notification::create([
                'user_id' => $nextApprover->id,
                'channel' => 'whatsapp',
                'message' => 'Terdapat pengajuan cuti yang menunggu persetujuan Anda.',
                'sent_at' => now(),
            ]);
        }
    }

    /**
     * Notifikasi ke pegawai
     */
    private function notifyEmployee(
        LeaveRequest $leaveRequest,
        string $message
        ): void
    {
        Notification::create([
            'user_id' => $leaveRequest->employee->user_id,
            'channel' => 'whatsapp',
            'message' => $message,
            'sent_at' => now(),
        ]);
    }

    /**
     * Notifikasi ke bagian kepegawaian
     */
    private function notifyKepegawaian(LeaveRequest $leaveRequest): void
    {
        $kepegawaianUsers = User::where('role', 'kepegawaian')->get();

        foreach ($kepegawaianUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'channel' => 'whatsapp',
                'message' => 'Pengajuan cuti telah disetujui dan siap diarsipkan.',
                'sent_at' => now(),
            ]);
        }
    }
}
