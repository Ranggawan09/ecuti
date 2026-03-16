<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permintaan dan Pemberian Cuti</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        .container {
            max-width: 21cm;
            margin: 0 auto;
            padding: 0.5cm;
            background: white;
        }

        .header {
            text-align: right;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .title {
            text-align: center;
            margin-bottom: 5px;
        }

        .title h2 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .title .nomor {
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            font-weight: bold;
        }

        .no-border {
            border: none;
        }

        .checkbox-list {
            list-style: none;
            padding-left: 0;
        }

        .checkbox-list li {
            margin-bottom: 2px;
        }

        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
        }

        .checkbox.checked::before {
            content: '√';
            font-weight: bold;
        }

        .signature-section {
            margin-top: 10px;
            text-align: center;
        }

        .signature-box {
            display: inline-block;
            min-width: 200px;
            text-align: center;
        }

        .signature-image {
            max-height: 60px;
            margin: 5px 0;
        }

        .catatan-table td {
            padding: 2px 4px;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            Jombang, {{ \Carbon\Carbon::parse($leaveRequest->created_at)->locale('id')->isoFormat('D MMMM YYYY') }}<br>
            <strong>Kepada:</strong><br>
            Yth. Ketua Pengadilan Negeri Jombang<br>
            di - <u>J O M B A N G</u>
        </div>

        <!-- Title -->
        <div class="title">
            <h2>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</h2>
            <div class="nomor">Nomor: {{ $leaveRequest->id }}/KPN.W14.U19/KP.5.3/XII/{{ \Carbon\Carbon::parse($leaveRequest->created_at)->format('Y') }}</div>
        </div>

        <!-- Data Pegawai -->
        <table>
            <tr>
                <th colspan="4" style="background-color: #f0f0f0;">I. Data Pegawai:</th>
            </tr>
            <tr>
                <td style="width: 20%;">Nama</td>
                <td style="width: 30%;">{{ $leaveRequest->employee->user->nama ?? '-' }}</td>
                <td style="width: 20%;">NIP. {{ $leaveRequest->employee->user->nip ?? '-' }}</td>
                <td style="width: 30%;"></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>{{ $leaveRequest->employee->jabatan ?? '-' }}</td>
                <td colspan="2">Gol. Ruang: {{ $leaveRequest->employee->golongan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td colspan="3">{{ $leaveRequest->employee->unit_kerja ?? 'Pengadilan Negeri Jombang Kelas I B' }}</td>
            </tr>
        </table>

        <!-- Jenis Cuti -->
        <table>
            <tr>
                <th colspan="2" style="background-color: #f0f0f0;">II. Jenis cuti yang diambil:</th>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <ul class="checkbox-list">
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti Tahunan' ? 'checked' : '' }}"></span> 1. Cuti Tahunan</li>
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti Sakit' ? 'checked' : '' }}"></span> 3. Cuti Sakit.</li>
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti Karena Alasan Penting' ? 'checked' : '' }}"></span> 5. Cuti karena alasan Penting.</li>
                    </ul>
                </td>
                <td style="width: 50%;">
                    <ul class="checkbox-list">
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti Besar' ? 'checked' : '' }}"></span> 2. Cuti Besar.</li>
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti Melahirkan' ? 'checked' : '' }}"></span> 4. Cuti Melahirkan.</li>
                        <li><span class="checkbox {{ $leaveRequest->leaveType->name == 'Cuti di Luar Tanggungan Negara' ? 'checked' : '' }}"></span> 6. Cuti di luar tanggungan Negara.</li>
                    </ul>
                </td>
            </tr>
        </table>

        <!-- Alasan Cuti -->
        <table>
            <tr>
                <th style="background-color: #f0f0f0;">III. Alasan Cuti:</th>
            </tr>
            <tr>
                <td>{{ $leaveRequest->reason ?? 'Kepentingan Keluarga;' }}</td>
            </tr>
        </table>

        <!-- Lamanya Cuti -->
        <table>
            <tr>
                <th colspan="3" style="background-color: #f0f0f0;">IV. Lamanya Cuti</th>
            </tr>
            <tr>
                <td style="width: 30%;">Selama: {{ $leaveRequest->total_days }} hari</td>
                <td style="width: 40%;">(Hari/Bulan/Tahun)</td>
                <td style="width: 30%;">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d F Y') }}</td>
            </tr>
        </table>

        <!-- Catatan Cuti -->
        <table class="catatan-table">
            <tr>
                <th colspan="4" style="background-color: #f0f0f0;">V. Catatan Cuti:</th>
                <th rowspan="6" style="width: 30%; text-align: center; vertical-align: middle;">
                    <div>VI. Alamat selama menjalankan Cuti:</div>
                    <div style="margin-top: 5px;">{{ $leaveRequest->address_during_leave ?? 'Surabaya;' }}</div>
                    <div style="margin-top: 10px;">Telephone:</div>
                    <div style="margin-top: 5px;">:</div>
                </th>
            </tr>
            <tr>
                <th style="width: 15%;">Tahun</th>
                <th style="width: 10%;">Sisa</th>
                <th style="width: 20%;">Keterangan</th>
                <th style="width: 25%;"></th>
            </tr>
            @php
                $currentYear = \Carbon\Carbon::now()->year;
            @endphp
            <tr>
                <td>{{ $currentYear - 2 }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>{{ $currentYear - 1 }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>{{ $currentYear }}</td>
                <td>{{ $leaveRequest->total_days }}</td>
                <td>Diambil 1 Hari Sisa 3 Hari</td>
                <td></td>
            </tr>
        </table>

        <!-- Hormat saya -->
        <table>
            <tr>
                <td style="width: 70%;"></td>
                <td style="width: 30%; text-align: center;">
                    <div>Hormat saya,</div>
                    @if($leaveRequest->employee->signature_path)
                        <img src="{{ asset('storage/' . $leaveRequest->employee->signature_path) }}" class="signature-image" alt="Tanda Tangan">
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                    <div style="border-bottom: 1px solid #000; display: inline-block; min-width: 150px; text-align: center;">
                        <strong>{{ $leaveRequest->employee->user->nama ?? '-' }}</strong>
                    </div>
                    <div>NIP. {{ $leaveRequest->employee->user->nip ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <!-- Pertimbangan Atasan Langsung -->
        <table>
            <tr>
                <th colspan="4" style="background-color: #f0f0f0;">VII. Pertimbangan Atasan Langsung:</th>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="checkbox {{ $leaveRequest->approvalAtasanLangsung && $leaveRequest->approvalAtasanLangsung->status == 'disetujui' ? 'checked' : '' }}"></span> Disetujui
                </td>
                <td style="width: 25%;">
                    <span class="checkbox"></span> Perubahan
                </td>
                <td style="width: 25%;">
                    <span class="checkbox"></span> Ditangguhkan
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $leaveRequest->approvalAtasanLangsung && $leaveRequest->approvalAtasanLangsung->status == 'tidak_disetujui' ? 'checked' : '' }}"></span> Tidak disetujui
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <div>Panitera,</div>
                    @if($leaveRequest->employee->atasanLangsung && $leaveRequest->employee->atasanLangsung->employee && $leaveRequest->employee->atasanLangsung->employee->signature_path)
                        <img src="{{ asset('storage/' . $leaveRequest->employee->atasanLangsung->employee->signature_path) }}" class="signature-image" alt="Tanda Tangan Atasan">
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                    <div style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; text-align: center;">
                        <strong>{{ $leaveRequest->employee->atasanLangsung->nama ?? '-' }}</strong>
                    </div>
                    <div>NIP. {{ $leaveRequest->employee->atasanLangsung->nip ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <!-- Keputusan Pejabat -->
        <table>
            <tr>
                <th colspan="4" style="background-color: #f0f0f0;">VIII. Keputusan Pejabat yang memberikan Cuti:</th>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="checkbox {{ $leaveRequest->status == 'disetujui' ? 'checked' : '' }}"></span> Disetujui
                </td>
                <td style="width: 25%;">
                    <span class="checkbox"></span> Perubahan
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $leaveRequest->status == 'ditangguhkan' ? 'checked' : '' }}"></span> Ditangguhkan
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $leaveRequest->status == 'tidak_disetujui' ? 'checked' : '' }}"></span> Tidak Disetujui
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center; position: relative;">
                    <div>Ketua,</div>
                    <div style="position: absolute; right: 20px; top: 10px;">
                        <div style="width: 80px; height: 80px; border: 1px solid #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 9pt;">
                            STEMPEL
                        </div>
                    </div>
                    @if($leaveRequest->employee->atasanTidakLangsung && $leaveRequest->employee->atasanTidakLangsung->employee && $leaveRequest->employee->atasanTidakLangsung->employee->signature_path)
                        <img src="{{ asset('storage/' . $leaveRequest->employee->atasanTidakLangsung->employee->signature_path) }}" class="signature-image" alt="Tanda Tangan Ketua">
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                    <div style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; text-align: center;">
                        <strong>{{ $leaveRequest->employee->atasanTidakLangsung->nama ?? 'Yunizar Kilat Daya, S.H., M.H.' }}</strong>
                    </div>
                    <div>NIP. {{ $leaveRequest->employee->atasanTidakLangsung->nip ?? '197110613 199603 1 002' }}</div>
                </td>
            </tr>
        </table>

        <!-- Catatan -->
        <div style="margin-top: 10px; font-size: 9pt;">
            <strong>Catatan:</strong><br>
            1. Coret yang tidak perlu<br>
            2. Pilih salah satu dengan memberi tanda (√)<br>
            3. diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti<br>
            4. Diberi tanda centang oleh atasannya
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
