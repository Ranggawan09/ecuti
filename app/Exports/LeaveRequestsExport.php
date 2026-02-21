<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeaveRequestsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return LeaveRequest::with(['employee.user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Pegawai',
            'NIP',
            'Jenis Cuti',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Total Hari',
            'Alasan',
            'Alamat Selama Cuti',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    /**
     * @param LeaveRequest $leaveRequest
     * @return array
     */
    public function map($leaveRequest): array
    {
        return [
            $leaveRequest->id,
            $leaveRequest->employee->user->nama ?? '-',
            $leaveRequest->employee->user->nip ?? '-',
            $leaveRequest->leaveType->name ?? '-',
            $leaveRequest->start_date->format('d/m/Y'),
            $leaveRequest->end_date->format('d/m/Y'),
            $leaveRequest->total_days,
            $leaveRequest->reason,
            $leaveRequest->address_during_leave,
            $this->formatStatus($leaveRequest->status),
            $leaveRequest->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Format status for display
     */
    private function formatStatus($status): string
    {
        $statusMap = [
            'menunggu_atasan_langsung' => 'Menunggu Atasan Langsung',
            'menunggu_atasan_tidak_langsung' => 'Menunggu Atasan Tidak Langsung',
            'disetujui' => 'Disetujui',
            'perubahan' => 'Perubahan',
            'ditangguhkan' => 'Ditangguhkan',
            'tidak_disetujui' => 'Tidak Disetujui',
        ];

        return $statusMap[$status] ?? $status;
    }
}
