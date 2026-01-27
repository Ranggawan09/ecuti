<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Rahma Dewi',
                'nip' => '199711132020122010',
                'email' => 'pegawai@go.id',
                'phone_number' => '6281234567890',
                'role' => 'pegawai',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Deni Saptana',
                'nip' => '197309151994031003',
                'email' => 'atasan_langsung@go.id',
                'phone_number' => '6281234567891',
                'role' => 'atasan_langsung',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Yunizar Kilat Daya',
                'nip' => '197106131996031002',
                'email' => 'atasan_tidak_langsung@go.id',
                'phone_number' => '6281234567892',
                'role' => 'atasan_tidak_langsung',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff Kepegawaian',
                'nip' => '198000001999031001',
                'email' => 'kepegawaian@go.id',
                'phone_number' => '6281234567893',
                'role' => 'kepegawaian',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin',
                'nip' => '123456789',
                'email' => 'admin@go.id',
                'phone_number' => '6281234567555',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
