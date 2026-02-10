<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveBalance;
use App\Models\Employee;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::first();

        LeaveBalance::create([
            'employee_id' => $employee->id,
            'year' => now()->year,
            'total_days' => 12,
            'used_days' => 0,
            'remaining_days' => 12,
        ]);
    }
}
