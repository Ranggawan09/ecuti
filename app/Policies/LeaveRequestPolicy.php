<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->role === 'pegawai') {
            $employeeUserId = $leaveRequest->employee?->user_id
                ?? $leaveRequest->load('employee')->employee?->user_id;

            return $employeeUserId === $user->id;
        }

        return in_array($user->role, [
            'atasan_langsung',
            'atasan_tidak_langsung',
            'kepegawaian',
        ]);
    }

    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $leaveRequest->approvals()
            ->where('approver_id', $user->id)
            ->whereIn('status', [
                'menunggu_atasan_langsung',
                'menunggu_atasan_tidak_langsung',
            ])
            ->exists();
    }

    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->approve($user, $leaveRequest);
    }
}