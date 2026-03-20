<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        LeaveType::insert([
            ['name' => 'Cuti Tahunan', 'max_days' => 12, 'deduct_balance' => true],
            ['name' => 'Cuti Sakit', 'max_days' => 12, 'deduct_balance' => true],
            ['name' => 'Cuti Besar', 'max_days' => 12, 'deduct_balance' => true],
            ['name' => 'Cuti Melahirkan', 'max_days' => 12, 'deduct_balance' => true],
            ['name' => 'Cuti Alasan Penting', 'max_days' => 12, 'deduct_balance' => true],
            ['name' => 'Cuti di Luar Tanggungan Negara', 'max_days' => 12, 'deduct_balance' => true],
        ]);
    }
}
