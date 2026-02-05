<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Master Jenis Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }
        .header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #8b5cf6;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        table tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-yes {
            background-color: #d1fae5;
            color: #059669;
        }
        .badge-no {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .footer {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Master Jenis Cuti</h1>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 50%;">Nama Jenis Cuti</th>
                <th style="width: 22%; text-align: center;">Maks. Hari</th>
                <th style="width: 22%; text-align: center;">Potong Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveTypes as $index => $leaveType)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $leaveType->name }}</td>
                <td style="text-align: center;">
                    @if($leaveType->max_days)
                        {{ $leaveType->max_days }} hari
                    @else
                        <span style="color: #9ca3af;">Tidak ada batas</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <span class="badge {{ $leaveType->deduct_balance ? 'badge-yes' : 'badge-no' }}">
                        {{ $leaveType->deduct_balance ? 'Ya' : 'Tidak' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>Total: {{ count($leaveTypes) }} jenis cuti</span>
        <span>Sistem Cuti</span>
    </div>
</body>
</html>