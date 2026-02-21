<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Cuti Pegawai - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            display: inline-block;
        }
        .status-perubahan {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .status-menunggu-langsung {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-menunggu-tidak-langsung {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .status-disetujui {
            background-color: #d1fae5;
            color: #059669;
        }
        .status-tidak-disetujui {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .status-ditangguhkan {
            background-color: #fed7aa;
            color: #ea580c;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>DATA CUTI PEGAWAI</h1>
    <div class="subtitle">Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Pegawai</th>
                <th style="width: 12%;">NIP</th>
                <th style="width: 15%;">Jenis Cuti</th>
                <th style="width: 12%;">Tgl Mulai</th>
                <th style="width: 12%;">Tgl Selesai</th>
                <th style="width: 8%;">Total Hari</th>
                <th style="width: 16%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveRequests as $index => $leaveRequest)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $leaveRequest->employee->user->nama ?? '-' }}</td>
                <td>{{ $leaveRequest->employee->user->nip ?? '-' }}</td>
                <td>{{ $leaveRequest->leaveType->name ?? '-' }}</td>
                <td>{{ $leaveRequest->start_date->format('d/m/Y') }}</td>
                <td>{{ $leaveRequest->end_date->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $leaveRequest->total_days }}</td>
                <td>
                    @php
                        $statusClass = match($leaveRequest->status) {
                            'menunggu_atasan_langsung' => 'status-menunggu-langsung',
                            'menunggu_atasan_tidak_langsung' => 'status-menunggu-tidak-langsung',
                            'disetujui' => 'status-disetujui',
                            'perubahan' => 'status-perubahan',
                            'tidak_disetujui' => 'status-tidak-disetujui',
                            'ditangguhkan' => 'status-ditangguhkan',
                            default => 'status-perubahan'
                        };
                        $statusText = match($leaveRequest->status) {
                            'menunggu_atasan_langsung' => 'Menunggu Atasan Langsung',
                            'menunggu_atasan_tidak_langsung' => 'Menunggu Atasan Tidak Langsung',
                            'disetujui' => 'Disetujui',
                            'perubahan' => 'Perubahan',
                            'tidak_disetujui' => 'Tidak Disetujui',
                            'ditangguhkan' => 'Ditangguhkan',
                            default => ucfirst($leaveRequest->status)
                        };
                    @endphp
                    <span class="status {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #9ca3af;">
                    Tidak ada data cuti pegawai
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $leaveRequests->count() }} data cuti pegawai
    </div>
</body>
</html>
