<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123');

        $users = [
            // Admin
            [
                'nama' => 'Admin',
                'nip' => '123456789',
                'email' => 'admin@go.id',
                'whatsapp' => '6281234567555',
                'role' => 'admin',
                'roles' => ['admin'],
                'password' => $password,
            ],
            // Kepegawaian
            [
                'nama' => 'Staff Kepegawaian',
                'nip' => '198000001999031001',
                'email' => 'kepegawaian@go.id',
                'whatsapp' => '6281234567893',
                'role' => 'kepegawaian',
                'roles' => ['kepegawaian'],
                'password' => $password,
            ],
            // Pegawai
            [
                'nama' => 'Pegawai',
                'nip' => '199711132020122010',
                'email' => 'pegawai@go.id',
                'whatsapp' => '6281234567890',
                'role' => 'pegawai',
                'roles' => ['pegawai'],
                'password' => $password,
            ],
            // AL
            [
                'nama' => 'Atasan Langsung',
                'nip' => '197309151994031003',
                'email' => 'al@go.id',
                'whatsapp' => '6281234567891',
                'role' => 'atasan_langsung',
                'roles' => ['atasan_langsung'],
                'password' => $password,
            ],
            // ATL
            [
                'nama' => 'Atasan Tidak Langsung',
                'nip' => '197106131996031002',
                'email' => 'atl@go.id',
                'whatsapp' => '6281234567892',
                'role' => 'atasan_tidak_langsung',
                'roles' => ['atasan_tidak_langsung'],
                'password' => $password,
            ],
            // AL + ATL
            [
                'nama' => 'AL dan ATL',
                'nip' => '197106131996031003',
                'email' => 'alatl@go.id',
                'whatsapp' => '6281234567893',
                'role' => 'atasan_langsung',
                'roles' => ['atasan_langsung', 'atasan_tidak_langsung'],
                'password' => $password,
            ],
            // Pegawai + AL
            [
                'nama' => 'Pegawai dan AL',
                'nip' => '197106131996031004',
                'email' => 'pegawaial@go.id',
                'whatsapp' => '6281234567894',
                'role' => 'pegawai',
                'roles' => ['pegawai', 'atasan_langsung'],
                'password' => $password,
            ],
            // Pegawai + ATL
            [
                'nama' => 'Pegawai dan ATL',
                'nip' => '197106131996031005',
                'email' => 'pegawaiatl@go.id',
                'whatsapp' => '6281234567895',
                'role' => 'pegawai',
                'roles' => ['pegawai', 'atasan_tidak_langsung'],
                'password' => $password,
            ],
            // Pegawai + AL + ATL
            [
                'nama' => 'Pegawai AL dan ATL',
                'nip' => '197106131996031006',
                'email' => 'pegawaialatl@go.id',
                'whatsapp' => '6281234567896',
                'role' => 'pegawai',
                'roles' => ['pegawai', 'atasan_langsung', 'atasan_tidak_langsung'],
                'password' => $password,
            ],
            // Pegawai + Kepegawaian
            [
                'nama' => 'Pegawai dan HR',
                'nip' => '197106131996031007',
                'email' => 'pegawaihr@go.id',
                'whatsapp' => '6281234567897',
                'role' => 'pegawai',
                'roles' => ['pegawai', 'kepegawaian'],
                'password' => $password,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
