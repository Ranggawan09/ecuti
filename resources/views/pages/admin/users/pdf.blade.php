<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #8b5cf6;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-admin {
            background-color: #ddd6fe;
            color: #7c3aed;
        }
        .badge-kepegawaian {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .badge-atasan-langsung {
            background-color: #fef3c7;
            color: #d97706;
        }
        .badge-atasan-tidak-langsung {
            background-color: #fecdd3;
            color: #e11d48;
        }
        .badge-pegawai {
            background-color: #d1fae5;
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data User Sistem Cuti</h1>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 15%;">NIP</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 15%;">No WhatsApp</th>
                <th style="width: 15%;">Role</th>
                <th style="width: 10%;">Tgl Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->nip }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->whatsapp ?? '-' }}</td>
                <td>
                    @php
                        $roleMap = [
                            'admin' => 'Admin',
                            'kepegawaian' => 'Kepegawaian',
                            'atasan_langsung' => 'Atasan Langsung',
                            'atasan_tidak_langsung' => 'Atasan Tidak Langsung',
                            'pegawai' => 'Pegawai',
                        ];
                        $badgeClass = [
                            'admin' => 'badge-admin',
                            'kepegawaian' => 'badge-kepegawaian',
                            'atasan_langsung' => 'badge-atasan-langsung',
                            'atasan_tidak_langsung' => 'badge-atasan-tidak-langsung',
                            'pegawai' => 'badge-pegawai',
                        ];
                    @endphp
                    <span class="badge {{ $badgeClass[$user->role] ?? '' }}">
                        {{ $roleMap[$user->role] ?? $user->role }}
                    </span>
                </td>
                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($users) }} user</p>
    </div>
</body>
</html>