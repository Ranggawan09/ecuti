<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permintaan dan Pemberian Cuti</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.2cm 1.0cm 1.2cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
            background: #fff;
        }
        .wrap {
            width: 100%;
        }

        /* Header kanan */
        .hdr-right {
            text-align: right;
            font-size: 8pt;
            line-height: 1.5;
            margin-bottom: 3px;
        }

        /* Judul tengah */
        .judul {
            text-align: center;
            margin-bottom: 4px;
        }
        .judul h2 {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .judul .nomor {
            font-size: 8pt;
            font-weight: bold;
        }

        /* Tables */
        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 2px;
            page-break-inside: avoid;
        }
        td, th {
            border: 1px solid #000;
            padding: 1.5px 3px;
            font-size: 8pt;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .bold   { font-weight: bold; }
        .center { text-align: center; }
        .right  { text-align: right; }
        .noborder { border: none !important; }

        /* Footer catatan */
        .kaki {
            font-size: 6.5pt;
            margin-top: 2px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="wrap">
@php
    $emp        = $leaveRequest->employee;
    $empUser    = optional($emp)->user;

    /* Atasan Langsung = User model */
    $aL         = optional($emp)->atasanLangsung;       // User
    $aLEmp      = optional($aL)->employee;              // Employee

    /* Atasan Tidak Langsung = User model */
    $aTL        = optional($emp)->atasanTidakLangsung;  // User
    $aTLEmp     = optional($aTL)->employee;             // Employee

    /* Approval status */
    $appL       = $leaveRequest->approvalAtasanLangsung;
    $stL        = optional($appL)->status;  // disetujui / tidak_disetujui / ditangguhkan / perubahan

    $stFinal    = $leaveRequest->status;

    /* Masa kerja */
    $masaKerja  = optional($emp)->masa_kerja;
    $masaTahun  = $masaKerja->tahun ?? 0;
    $masaBulan  = $masaKerja->bulan ?? 0;
    $masaKerjaStr = $masaTahun . ' Tahun ' . $masaBulan . ' Bulan';

    /* Golongan */
    $golongan   = optional($emp)->golongan ?? '-';

    /* Alamat & telepon */
    $address    = $leaveRequest->address_during_leave ?? '-';
    $phone      = optional($empUser)->whatsapp ?? optional($empUser)->phone ?? '-';

    /* Leave balances — 3 tahun terakhir */
    $cy       = \Carbon\Carbon::now()->year;
    $balances = optional($emp)->leaveBalances ?? collect();
    $balN2    = $balances->where('year', $cy - 2)->first();
    $balN1    = $balances->where('year', $cy - 1)->first();
    $balN     = $balances->where('year', $cy)->first();

    /* Helper: keterangan saldo */
    $keteranganBal = function($bal) use ($leaveRequest) {
        if (!$bal) return '-';
        $diambil = $bal->used_days ?? 0;
        $sisa    = $bal->remaining_days ?? 0;
        if ($diambil > 0) {
            return 'Diambil ' . $diambil . ' Hari Sisa ' . $sisa . ' Hari';
        }
        return 'Sisa ' . $sisa . ' Hari';
    };

    /* Terbilang */
    $tb = [
        0=>'nol',1=>'satu',2=>'dua',3=>'tiga',4=>'empat',5=>'lima',
        6=>'enam',7=>'tujuh',8=>'delapan',9=>'sembilan',10=>'sepuluh',
        11=>'sebelas',12=>'dua belas',13=>'tiga belas',14=>'empat belas',
        15=>'lima belas',16=>'enam belas',17=>'tujuh belas',18=>'delapan belas',
        19=>'sembilan belas',20=>'dua puluh',21=>'dua puluh satu',
        22=>'dua puluh dua',23=>'dua puluh tiga',24=>'dua puluh empat',
        25=>'dua puluh lima',26=>'dua puluh enam',27=>'dua puluh tujuh',
        28=>'dua puluh delapan',29=>'dua puluh sembilan',30=>'tiga puluh',
    ];
    $days    = (int)$leaveRequest->total_days;
    $dayText = $tb[$days] ?? $days;

    /* Bulan romawi */
    $rm      = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    $bmnCrt  = (int)\Carbon\Carbon::parse($leaveRequest->created_at)->format('n');
    $rBln    = $rm[$bmnCrt - 1];

    /* Tanggal */
    $tglSurat   = \Carbon\Carbon::parse($leaveRequest->created_at)->locale('id')->isoFormat('D MMMM YYYY');
    $tglMulai   = \Carbon\Carbon::parse($leaveRequest->start_date)->locale('id')->isoFormat('D MMMM YYYY');
    $tglSelesai = \Carbon\Carbon::parse($leaveRequest->end_date)->locale('id')->isoFormat('D MMMM YYYY');
    $tahunSurat = \Carbon\Carbon::parse($leaveRequest->created_at)->format('Y');

    /* Rentang tanggal cuti: "12-14 Oktober 2025" / "30 Sep - 2 Okt 2025" / "30 Des 2025 - 2 Jan 2026" */
    $dtMulai   = \Carbon\Carbon::parse($leaveRequest->start_date)->locale('id');
    $dtSelesai = \Carbon\Carbon::parse($leaveRequest->end_date)->locale('id');
    if ($dtMulai->year === $dtSelesai->year && $dtMulai->month === $dtSelesai->month) {
        // Bulan & tahun sama → "12-14 Oktober 2025"
        $lamaCutiStr = $dtMulai->day . '-' . $dtSelesai->isoFormat('D MMMM YYYY');
    } elseif ($dtMulai->year === $dtSelesai->year) {
        // Tahun sama tapi bulan beda → "30 September - 2 Oktober 2025"
        $lamaCutiStr = $dtMulai->isoFormat('D MMMM') . ' - ' . $dtSelesai->isoFormat('D MMMM YYYY');
    } else {
        // Tahun beda → "30 Desember 2025 - 2 Januari 2026"
        $lamaCutiStr = $dtMulai->isoFormat('D MMMM YYYY') . ' - ' . $dtSelesai->isoFormat('D MMMM YYYY');
    }

    /* Nomor surat dari DB; fallback ke auto-generate */
    $nomorSurat = $leaveRequest->nomor_surat
        ?? ($leaveRequest->no_urut . '/KPN.W14.U5/KP5.3/' . $rBln . '/' . $tahunSurat);

    /* Nama jenis cuti */
    $namaJenisCuti = optional($leaveRequest->leaveType)->name ?? '';

    /* Unit kerja kota (ambil kata terakhir unit_kerja sebagai kota) */
    $unitKerja  = optional($emp)->unit_kerja ?? 'Jombang';
    // Kota surat: gunakan field terpisah jika ada, fallback ke unit kerja
    $kotaSurat  = optional($empUser)->kota ?? 'Jombang';
@endphp

{{-- ============================================================ --}}
{{-- HEADER: Tanggal + Kepada (rata kanan)                       --}}
{{-- ============================================================ --}}
<div class="hdr-right">
    {{ $kotaSurat }},&nbsp;&nbsp;{{ $tglSurat }}<br>
    Kepada<br>
    Yth. Ketua Pengadilan Negeri {{ $kotaSurat }}<br>
    di &nbsp;– &nbsp;<span style="letter-spacing:2px">{{ strtoupper($kotaSurat) }}</span>
</div>

{{-- ============================================================ --}}
{{-- JUDUL (tengah)                                              --}}
{{-- ============================================================ --}}
<div class="judul">
    <h2>Formulir Permintaan Dan Pemberian Cuti</h2>
    <div class="nomor">Nomor :&nbsp;{{ $nomorSurat }}</div>
</div>

{{-- ============================================================ --}}
{{-- I. DATA PEGAWAI                                             --}}
{{-- ============================================================ --}}
<table>
    <colgroup>
        <col style="width:13%">
        <col style="width:37%">
        <col style="width:13%">
        <col style="width:37%">
    </colgroup>
    <tr>
        <td colspan="4" class="bold">I.&nbsp;&nbsp;Data Pegawai :</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td class="bold">{{ optional($empUser)->nama ?? '-' }}</td>
        <td>NIP.</td>
        <td>{{ optional($empUser)->nip ?? '-' }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>{{ optional($emp)->jabatan ?? '-' }}</td>
        <td>Gol. Ruang</td>
        <td>{{ $golongan }}</td>
    </tr>
    <tr>
        <td>Unit Kerja</td>
        <td>{{ $unitKerja }}</td>
        <td>Masa Kerja</td>
        <td>{{ $masaKerjaStr }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- II. JENIS CUTI YANG DIAMBIL                                 --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="bold">II.&nbsp;&nbsp;Jenis cuti yang di ambil :</td>
    </tr>
    <tr>
        <td style="width:38%">1.Cuti Tahunan</td>
        <td style="width:12%; text-align:center">{{ $namaJenisCuti=='Cuti Tahunan' ? '√' : '' }}</td>
        <td style="width:38%">2.Cuti Besar.</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Besar' ? '√' : '' }}</td>
    </tr>
    <tr>
        <td>3.Cuti Sakit.</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Sakit' ? '√' : '' }}</td>
        <td>4.Cuti Melahirkan.</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Melahirkan' ? '√' : '' }}</td>
    </tr>
    <tr>
        <td>5.Cuti karena alasan Penting.</td>
        <td style="text-align:center">{{ in_array($namaJenisCuti,['Cuti Karena Alasan Penting','Cuti karena alasan Penting']) ? '√' : '' }}</td>
        <td>6.Cuti di Luar Tanggungan Negara.</td>
        <td style="text-align:center">{{ in_array($namaJenisCuti,['Cuti di Luar Tanggungan Negara','Cuti Diluar Tanggungan Negara']) ? '√' : '' }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- III. ALASAN CUTI                                            --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td class="bold">III.&nbsp;&nbsp;Alasan Cuti :</td>
    </tr>
    <tr>
        <td style="height:18px">{{ $leaveRequest->reason ?? '-' }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- IV. LAMANYA CUTI                                            --}}
{{-- ============================================================ --}}
<table>
    <colgroup>
        <col style="width:10%">
        <col style="width:13%">
        <col style="width:35%">
        <col style="width:42%">
    </colgroup>
    <tr>
        <td colspan="4" class="bold">IV.&nbsp;&nbsp;Lamanya Cuti</td>
    </tr>
    <tr>
        <td>Selama :</td>
        <td>{{ $days }} hari</td>
        <td style="text-align:center">({{ $lamaCutiStr }})</td>
        <td style="text-align:center">{{ $tglMulai }} s.d. {{ $tglSelesai }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- V. CATATAN CUTI                                             --}}
{{-- ============================================================ --}}
{{--
    Layout: judul baris penuh, lalu dua sub-tabel berdampingan dalam
    wrapper tabel tanpa border agar kedua sisi punya kotak border sendiri.
--}}

{{-- Baris judul V --}}
<table>
    <tr>
        <td class="bold">V.&nbsp;&nbsp;Catatan Cuti :</td>
    </tr>
</table>

{{-- Wrapper: dua sub-tabel berdampingan --}}
<table style="margin-bottom:2px; border:none">
    <colgroup>
        <col style="width:52%">
        <col style="width:4%">
        <col style="width:44%">
    </colgroup>
    <tr style="border:none">
        {{-- Sub-tabel KIRI: Cuti Tahunan --}}
        <td style="border:none; padding:0; vertical-align:top">
            <table style="width:100%; margin:0; border-collapse:collapse; table-layout:fixed; border: 1px solid #000">
                <colgroup>
                    <col style="width:30%">
                    <col style="width:20%">
                    <col style="width:50%">
                </colgroup>
                <tr>
                    <td colspan="3" class="bold" style="text-align:center">1. Cuti Tahunan</td>
                </tr>
                <tr>
                    <td class="bold">Tahun</td>
                    <td class="bold" style="text-align:center">Sisa</td>
                    <td class="bold">Keterangan</td>
                </tr>
                <tr>
                    <td>{{ $cy - 2 }}</td>
                    <td style="text-align:center">{{ optional($balN2)->remaining_days ?? '-' }}</td>
                    <td style="font-size:7pt">{{ $keteranganBal($balN2) }}</td>
                </tr>
                <tr>
                    <td>{{ $cy - 1 }}</td>
                    <td style="text-align:center">{{ optional($balN1)->remaining_days ?? '-' }}</td>
                    <td style="font-size:7pt">{{ $keteranganBal($balN1) }}</td>
                </tr>
                <tr>
                    <td>{{ $cy }}</td>
                    <td style="text-align:center">{{ optional($balN)->remaining_days ?? '-' }}</td>
                    <td style="font-size:7pt">{{ $keteranganBal($balN) }}</td>
                </tr>
            </table>
        </td>

        {{-- Jarak tengah --}}
        <td style="border:none; padding:0"></td>

        {{-- Sub-tabel KANAN: jenis cuti lainnya --}}
        <td style="border:none; padding:0; vertical-align:top">
            <table style="width:100%; margin:0; border-collapse:collapse; table-layout:fixed; border: 1px solid #000">
                <tr>
                    <td>2. Cuti Besar</td>
                </tr>
                <tr>
                    <td>3. Cuti Sakit</td>
                </tr>
                <tr>
                    <td>4. Cuti Melahirkan</td>
                </tr>
                <tr>
                    <td>5. Cuti karena alasan penting</td>
                </tr>
                <tr>
                    <td>6. Cuti diluar tanggungan negara</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- VI. ALAMAT SELAMA MENJALANKAN CUTI + TANDA TANGAN PEGAWAI  --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="3" class="bold">VI.&nbsp;&nbsp;Alamat selama menjalankan Cuti :</td>
    </tr>
    <colgroup>
        <col style="width:44%">
        <col style="width:12%">
        <col style="width:44%">
    </colgroup>
    <tr>
        <td style="height:70px; vertical-align:top; padding:2px 4px">
            {{ $address }}
        </td>
        <td style="vertical-align:top; padding:2px 4px">
            Telephone:
        </td>
        <td style="vertical-align:top; padding:2px 4px">
            {{ $phone }}<br>
            <div style="text-align:right; padding-right:4px">
                Hormat saya ,<br>
                @if(optional($emp)->signature_path)
                    <img src="{{ asset('storage/' . $emp->signature_path) }}"
                         style="max-height:32px; max-width:90px; display:block; margin-left:auto; margin-bottom:1px"
                         alt="TTD">
                @else
                    <div style="height:32px"></div>
                @endif
                <strong>{{ optional($empUser)->nama ?? '-' }}</strong><br>
                NIP. {{ optional($empUser)->nip ?? '-' }}
            </div>
        </td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- VII. PERTIMBANGAN ATASAN LANGSUNG                           --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="bold">VII.&nbsp;Pertimbangan Atasan Langsung :</td>
    </tr>
    <tr>
        <td style="width:25%">
            Disetujui{{ $stL=='disetujui' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Perubahan{{ $stL=='perubahan' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Ditangguhkan{{ $stL=='ditangguhkan' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Tidak disetujui{{ $stL=='tidak_disetujui' ? ' ✓' : '' }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="height:65px; vertical-align:bottom; padding:3px 6px">
            <div style="text-align:right">
                Panitera ,<br>
                @if(optional($aLEmp)->signature_path)
                    <img src="{{ asset('storage/' . $aLEmp->signature_path) }}"
                         style="max-height:30px; max-width:110px; display:block; margin-left:auto; margin-bottom:1px"
                         alt="TTD">
                @else
                    <div style="height:30px"></div>
                @endif
                <strong>{{ optional($aL)->nama ?? '' }}</strong><br>
                NIP. {{ optional($aL)->nip ?? '' }}
            </div>
        </td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- VIII. KEPUTUSAN PEJABAT                                     --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="bold">VIII.&nbsp;Keputusan Pejabat yang &nbsp;memberikan Cuti:</td>
    </tr>
    <tr>
        <td style="width:25%">
            Disetujui{{ $stFinal=='disetujui' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Perubahan{{ $stFinal=='perubahan' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Ditangguhkan{{ $stFinal=='ditangguhkan' ? ' ✓' : '' }}
        </td>
        <td style="width:25%">
            Tidak Disetujui{{ $stFinal=='tidak_disetujui' ? ' ✓' : '' }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="height:65px; vertical-align:bottom; padding:3px 6px">
            <div style="text-align:right">
                Ketua ,<br>
                @if(optional($aTLEmp)->signature_path)
                    <img src="{{ asset('storage/' . $aTLEmp->signature_path) }}"
                         style="max-height:30px; max-width:110px; display:block; margin-left:auto; margin-bottom:1px"
                         alt="TTD">
                @else
                    <div style="height:30px"></div>
                @endif
                <strong>{{ optional($aTL)->nama ?? '' }}</strong><br>
                NIP. {{ optional($aTL)->nip ?? '' }}
            </div>
        </td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- CATATAN KAKI                                                --}}
{{-- ============================================================ --}}
<div class="kaki">
    Catatan<br>
    1.Coret yang tidak perlu<br>
    2. Pilih salah satu dengan memberi tanda (O)<br>
    3. diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti<br>
    4. Diberi tanda centang dan alasannya
</div>

</div>
<script>
    window.onload = function () { window.print(); }
</script>
</body>
</html>
