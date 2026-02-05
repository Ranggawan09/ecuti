<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Approval;
use App\Models\LeaveRequest;
use App\Models\User;

class ApprovalSeeder extends Seeder
{
    public function run(): void
    {
        $leave = LeaveRequest::first();
        $atasanLangsung = User::where('role', 'atasan_langsung')->first();
        $atasanTidakLangsung = User::where('role', 'atasan_tidak_langsung')->first();

        Approval::insert([
            [
                'leave_request_id' => $leave->id,
                'approver_id' => $atasanLangsung->id,
                'level' => 'atasan_langsung',
                'status' => 'menunggu',
            ],
            [
                'leave_request_id' => $leave->id,
                'approver_id' => $atasanTidakLangsung->id,
                'level' => 'atasan_tidak_langsung',
                'status' => 'menunggu',
            ],
        ]);
    }
}
