<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = User::where('role', 'pegawai')->first();
        $atasanLangsung = User::where('role', 'atasan_langsung')->first();
        $atasanTidakLangsung = User::where('role', 'atasan_tidak_langsung')->first();

        Employee::create([
            'user_id' => $pegawai->id,
            'jabatan' => 'Klerk - Analis Perkara Peradilan',
            'golongan' => 'III/b',
            'unit_kerja' => 'Pengadilan Negeri Jombang Kelas I.A',
            'atasan_langsung_id' => $atasanLangsung->id,
            'atasan_tidak_langsung_id' => $atasanTidakLangsung->id,
        ]);
    }
}
