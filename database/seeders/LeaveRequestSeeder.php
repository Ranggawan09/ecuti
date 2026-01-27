<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::first();
        $leaveType = LeaveType::where('name', 'Cuti Tahunan')->first();

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => '2025-12-24',
            'end_date' => '2025-12-24',
            'total_days' => 1,
            'reason' => 'Kepentingan keluarga',
            'address_during_leave' => 'Surabaya',
            'status' => 'menunggu_atasan_langsung',
        ]);
    }
}
