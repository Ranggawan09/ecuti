<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pengajuan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mocking year 2027 for testing leave balance feature
        //\Illuminate\Support\Carbon::setTestNow('2027-01-01');

        // Simulasi update jatah cuti otomatis saat memasuki tahun baru 2027
        if (!$this->app->runningInConsole() && \Illuminate\Support\Facades\Schema::hasTable('leave_balances')) {
            $year = now()->year;
            if ($year == 2027 && \App\Models\LeaveBalance::where('year', 2027)->count() === 0) {
                $this->initializeNewYearBalances($year);
            }
        }

        View::composer('*', function ($view) {
            $pendingCount = LeaveRequest::where('status', 'draft')->count();
            $view->with('pendingCount', $pendingCount);
        });
    }

    /**
     * Initialize leave balances for all employees for a new year
     */
    private function initializeNewYearBalances(int $year): void
    {
        $employees = \App\Models\Employee::all();
        $leaveTypes = \App\Models\LeaveType::all();
        $lastYear = $year - 1;

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $type) {
                $name = strtolower($type->name);
                $isTahunan = str_contains($name, 'tahunan');
                $isSakit = str_contains($name, 'sakit');

                // Jatah tahun ini (2027)
                if ($isTahunan || $isSakit) {
                    $totalDays = $type->max_days;
                } else {
                    $lastBalance = \App\Models\LeaveBalance::where('employee_id', $employee->id)
                        ->where('leave_type_id', $type->id)
                        ->where('year', $lastYear)
                        ->first();
                    $totalDays = $lastBalance ? $lastBalance->remaining_days : $type->max_days;
                }

                // KHUSUS TAHUNAN: Update sisa cuti tahun lalu (2026) sebelum pindah ke tahun baru
                if ($isTahunan) {
                    $lastBalance = \App\Models\LeaveBalance::where('employee_id', $employee->id)
                        ->where('leave_type_id', $type->id)
                        ->where('year', $lastYear)
                        ->first();

                    if ($lastBalance) {
                        // Hitung jumlah hari yang ditangguhkan di tahun lalu
                        $deferredDays = \App\Models\LeaveRequest::where('employee_id', $employee->id)
                            ->where('leave_type_id', $type->id)
                            ->where('status', 'ditangguhkan')
                            ->whereYear('start_date', $lastYear)
                            ->sum('total_days');

                        // Rule: 6 hari jika tidak ditangguhkan, atau 6 + ditangguhkan (max 12)
                        $maxCarryOver = 6 + $deferredDays;
                        if ($maxCarryOver > 12)
                            $maxCarryOver = 12;

                        // Update sisa tahun lalu sesuai aturan carry-over
                        $lastBalance->remaining_days = min($lastBalance->remaining_days, $maxCarryOver);
                        $lastBalance->save();
                    }
                }

                \App\Models\LeaveBalance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $type->id,
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
