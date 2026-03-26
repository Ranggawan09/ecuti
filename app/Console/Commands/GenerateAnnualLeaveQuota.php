<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;

class GenerateAnnualLeaveQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:generate-annual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate annual leave quotas for all active employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        // Cari master cuti tahunan, atau default ke salah satu cuti yang mengurangi saldo
        $mainLeaveType = LeaveType::where('name', 'like', '%Tahunan%')->first()
            ?? LeaveType::where('deduct_balance', true)->first();

        if (!$mainLeaveType) {
            $this->error('Tipe cuti yang mengurangi saldo tidak ditemukan di database.');
            return 1;
        }

        $maxDays = $mainLeaveType->max_days;
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Cek apakah saldo cuti tahun ini sudah ter-generate
            $existingBalance = LeaveBalance::where('employee_id', $employee->id)
                ->where('year', $currentYear)
                ->first();

            if ($existingBalance) {
                $this->info("Saldo cuti tahun {$currentYear} untuk Employee ID {$employee->id} sudah ada. Melewati.");
                continue;
            }

            // Hitung cuti tahun sebelumnya yang ditangguhkan (is_penangguhan = true dan disetujui)
            // Sesuai requirement: "samakan dengan jumlah cuti yang ditangguhkan (n-1)"
            $postponedLeavesDays = LeaveRequest::where('employee_id', $employee->id)
                ->where('is_penangguhan', true)
                ->where('status', 'disetujui')
                ->whereYear('created_at', $previousYear)
                ->sum('total_days');

            // Hapus sisa cuti tahun lalu (n-1) karena sisanya sudah menjadi 0 jika tidak ada yg ditangguhkan
            // Atau tetap diset 0 untuk sisa tahun lalu secara clean slate n-1 = 0
            $prevBalance = LeaveBalance::where('employee_id', $employee->id)
                ->where('year', $previousYear)
                ->first();

            if ($prevBalance) {
                $prevBalance->update(['remaining_days' => 0]);
            }

            // Buat saldo untuk tahun berjalan (n)
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'year' => $currentYear,
                'total_days' => $maxDays,
                'used_days' => 0,
                'remaining_days' => $maxDays,
                'carried_over_days' => $postponedLeavesDays,
            ]);

            $this->info("Berhasil membuat saldo untuk Employee ID {$employee->id} dengan Sisa Cuti Bawaan: {$postponedLeavesDays} hari.");
        }

        $this->info('Sukses melakukan generate kuota cuti tahunan.');
        return 0;
    }
}
