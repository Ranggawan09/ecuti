<?php

namespace App\Exports;

use App\Models\LeaveType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeaveTypesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return LeaveType::orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Jenis Cuti',
            'Maksimum Hari',
            'Potong Saldo',
        ];
    }

    public function map($leaveType): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $leaveType->name,
            $leaveType->max_days ?? '-',
            $leaveType->deduct_balance ? 'Ya' : 'Tidak',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}