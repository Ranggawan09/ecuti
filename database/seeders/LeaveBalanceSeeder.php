<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveBalance;
use App\Models\Employee;
use App\Models\LeaveType;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();
        
        $years = [2024, 2025, 2026];

        foreach ($employees as $employee) {
            foreach ($years as $year) {
                foreach ($leaveTypes as $type) {
                    // Default quota based on leave type if max_days is set, else default to 12
                    $totalDays = $type->max_days ?? 12;
                    
                    // Set sisa cuti 6 hari untuk 2024 dan 2025
                    $remainingDays = in_array($year, [2024, 2025]) ? 6 : $totalDays;
                    $usedDays = $totalDays - $remainingDays;

                    LeaveBalance::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'leave_type_id' => $type->id,
                            'year' => $year,
                        ],
                        [
                            'total_days' => $totalDays,
                            'used_days' => $usedDays,
                            'remaining_days' => $remainingDays,
                            'carried_over_days' => 0,
                        ]
                    );
                }
            }
        }
    }
}
