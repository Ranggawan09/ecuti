<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::orderBy('nama')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIP',
            'Email',
            'No WhatsApp',
            'Role',
            'Dibuat Pada',
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $roleMap = [
            'admin' => 'Admin',
            'kepegawaian' => 'Kepegawaian',
            'atasan_langsung' => 'Atasan Langsung',
            'atasan_tidak_langsung' => 'Atasan Tidak Langsung',
            'pegawai' => 'Pegawai',
        ];

        return [
            $rowNumber,
            $user->nama,
            $user->nip,
            $user->email,
            $user->whatsapp ?? '-',
            $roleMap[$user->role] ?? $user->role,
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}