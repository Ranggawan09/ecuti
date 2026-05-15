<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Find atasan with specific role, checking both 'role' and 'roles' columns
        $atasanLangsung = User::where('role', 'atasan_langsung')
            ->orWhereJsonContains('roles', 'atasan_langsung')
            ->first();

        $atasanTidakLangsung = User::where('role', 'atasan_tidak_langsung')
            ->orWhereJsonContains('roles', 'atasan_tidak_langsung')
            ->first();

        // Fallback to first user if not found to avoid "id on null" error
        $atasanLangsung = $atasanLangsung ?? User::first();
        $atasanTidakLangsung = $atasanTidakLangsung ?? User::first();

        $pegawaiUsers = User::where('role', 'pegawai')
            ->orWhereJsonContains('roles', 'pegawai')
            ->get();

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
            $tahun = rand(1, 15);
            $bulan = rand(0, 11);

            Employee::create([
                'user_id' => $pegawai->id,
                'jabatan' => $positions[$index % count($positions)],
                'golongan' => $grades[$index % count($grades)],
                'unit_kerja' => 'Pengadilan Negeri Jombang Kelas I.A',
                'atasan_langsung_id' => $atasanLangsung->id,
                'atasan_tidak_langsung_id' => $atasanTidakLangsung->id,
                'masa_kerja_tahun' => $tahun,
                'masa_kerja_bulan' => $bulan,
                'tmt_masa_kerja' => now()->subYears($tahun)->subMonths($bulan),
            ]);
        }
    }
}
