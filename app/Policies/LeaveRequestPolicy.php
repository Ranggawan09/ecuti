<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
    /**
     * Pegawai hanya boleh melihat cutinya sendiri
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->role === 'pegawai') {
            return $leaveRequest->employee->user_id === $user->id;
        }

        return in_array($user->role, [
            'atasan_langsung',
            'atasan_tidak_langsung',
            'kepegawaian'
        ]);
    }

    /**
     * Approval hanya oleh approver yang sah & status sesuai
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $leaveRequest->approvals()
            ->where('approver_id', $user->id)
            ->where('status', 'menunggu')
            ->exists();
    }

    /**
     * Penolakan juga dibatasi approver sah
     */
    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->approve($user, $leaveRequest);
    }
}
