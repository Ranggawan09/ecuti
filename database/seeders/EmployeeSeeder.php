<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $atasanLangsung = User::where('role', 'atasan_langsung')->first();
        $atasanTidakLangsung = User::where('role', 'atasan_tidak_langsung')->first();
        $pegawaiUsers = User::where('role', 'pegawai')->get();

        // Job positions and grades for variety
        $positions = [
            'Klerk - Analis Perkara Peradilan',
            'Panitera Pengganti',
            'Jurusita',
            'Staf Administrasi Umum',
            'Staf Keuangan',
            'Staf Kepegawaian',
        ];

        $grades = [
            'III/a',
            'III/b',
            'III/c',
            'II/d',
            'II/c',
            'III/d',
        ];

        foreach ($pegawaiUsers as $index => $pegawai) {
            Employee::create([
                'user_id' => $pegawai->id,
                'jabatan' => $positions[$index % count($positions)],
                'golongan' => $grades[$index % count($grades)],
                'unit_kerja' => 'Pengadilan Negeri Jombang Kelas I.A',
                'atasan_langsung_id' => $atasanLangsung->id,
                'atasan_tidak_langsung_id' => $atasanTidakLangsung->id,
            ]);
        }
    }
}
