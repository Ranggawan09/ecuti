<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveBalance;
use App\Models\Employee;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Mocking year 2027 for testing leave balance feature
        \Carbon\Carbon::setTestNow('2027-01-01');

        $employees = Employee::all();
        $yearData = [
            2024 => 6,
            2025 => 6,
            2026 => 12,
        ];

        foreach ($employees as $employee) {
            foreach ($yearData as $year => $totalDays) {
                LeaveBalance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'year' => $year,
                ],
                [
                    'total_days' => $totalDays,
                    'used_days' => 0,
                    'remaining_days' => $totalDays,
                    'carried_over_days' => 0,
                ]
                );
            }
        }
    }
}
