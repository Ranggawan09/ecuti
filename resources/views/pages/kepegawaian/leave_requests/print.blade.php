<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permintaan dan Pemberian Cuti</title>
    <style>
        @page {
            size: A4;
            margin: 0.6cm 0.9cm 0.5cm 0.9cm;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
            background: white;
        }
        .wrap {
            width: 100%;
            max-width: 19.2cm;
            margin: 0 auto;
        }

        /* Header */
        .hdr-se {
            text-align: right;
            font-size: 6.8pt;
            line-height: 1.35;
            margin-bottom: 4px;
        }
        .hdr-addr {
            text-align: right;
            font-size: 8pt;
            line-height: 1.55;
            margin-bottom: 4px;
        }
        .judul {
            text-align: center;
            margin-bottom: 3px;
        }
        .judul h2 {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .judul .nomor {
            font-size: 8.5pt;
            font-weight: bold;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        td, th {
            border: 1px solid #000;
            padding: 1px 4px;
            font-size: 7.8pt;
            vertical-align: top;
            text-align: left;
        }
        .h {
            font-weight: bold;
        }

        /* Footer */
        .kaki {
            font-size: 6.5pt;
            margin-top: 2px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
<div class="wrap">
@php
    $emp        = $leaveRequest->employee;
    $empUser    = optional($emp)->user;

    /* Atasan Langsung = User model */
    $aL         = optional($emp)->atasanLangsung;
    $aLEmp      = optional($aL)->employee;

    /* Atasan Tidak Langsung = User model */
    $aTL        = optional($emp)->atasanTidakLangsung;
    $aTLEmp     = optional($aTL)->employee;

    /* Approval status */
    $appL       = $leaveRequest->approvalAtasanLangsung;
    $stL        = optional($appL)->status;  // disetujui / ditolak / ditangguhkan

    $stFinal    = $leaveRequest->status;

    /* Masa kerja */
    $masaTahun  = optional($emp)->masa_kerja_tahun ?? '-';

    /* Alamat & telepon */
    $address    = $leaveRequest->address_during_leave ?? '-';
    $phone      = optional($empUser)->phone ?? '-';

    /* Leave balances */
    $cy         = \Carbon\Carbon::now()->year;
    $balances   = optional($emp)->leaveBalances ?? collect();
    $balN2      = $balances->where('year', $cy-2)->first();
    $balN1      = $balances->where('year', $cy-1)->first();
    $balN       = $balances->where('year', $cy)->first();

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

    /* Bulan Romawi */
    $rm   = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    $bmn  = (int)\Carbon\Carbon::parse($leaveRequest->created_at)->format('n');
    $rBln = $rm[$bmn - 1];

    /* Tanggal format */
    $tglSurat   = \Carbon\Carbon::parse($leaveRequest->created_at)->locale('id')->isoFormat('D MMMM YYYY');
    $tglMulai   = \Carbon\Carbon::parse($leaveRequest->start_date)->locale('id')->isoFormat('D MMMM YYYY');
    $tglSelesai = \Carbon\Carbon::parse($leaveRequest->end_date)->locale('id')->isoFormat('D MMMM YYYY');
    $tahunSurat = \Carbon\Carbon::parse($leaveRequest->created_at)->format('Y');

    /* Nama jenis cuti */
    $namaJenisCuti = optional($leaveRequest->leaveType)->name ?? '';
@endphp

{{-- ============================================================ --}}
{{-- HEADER: SURAT EDARAN (rata kanan)                           --}}
{{-- ============================================================ --}}
<div class="hdr-se">
    SURAT EDARAN SEKRETARIS &nbsp;MAHKAMAH AGUNG &nbsp;REPUBLIK INDONESIA<br>
    NOMOR 13 TAHUN 2019<br>
    TENTANG TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL
</div>

{{-- ============================================================ --}}
{{-- TANGGAL + KEPADA (rata kanan)                               --}}
{{-- ============================================================ --}}
<div class="hdr-addr">
    {{ optional($emp)->unit_kerja_kota ?? 'Madiun' }},&nbsp;&nbsp;{{ $tglSurat }}<br>
    Kepada<br>
    Yth. Bapak Ketua Pengadilan Negeri Kota Madiun<br>
    di &nbsp;Madiun
</div>

{{-- ============================================================ --}}
{{-- JUDUL (tengah)                                              --}}
{{-- ============================================================ --}}
<div class="judul">
    <h2>Formulir Permintaan Dan Pemberian Cuti</h2>
    <div class="nomor">NOMOR :&nbsp;&nbsp;&nbsp;/KPN.W14.U5/ KP5.3/ {{ $rBln }} /{{ $tahunSurat }}</div>
</div>

{{-- ============================================================ --}}
{{-- I. DATA PEGAWAI                                             --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="h">I.&nbsp;&nbsp;&nbsp;DATA &nbsp;PEGAWAI</td>
    </tr>
    <tr>
        <td style="width:12%">Nama</td>
        <td style="width:38%">{{ optional($empUser)->nama ?? '-' }}</td>
        <td style="width:10%">NIP</td>
        <td>{{ optional($empUser)->nip ?? '-' }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>{{ optional($emp)->jabatan ?? '-' }}</td>
        <td>Gol. Ruano</td>
        <td>{{ optional($emp)->golongan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Unit Kerja</td>
        <td>{{ optional($emp)->unit_kerja ?? '-' }}</td>
        <td>Masa Kerja</td>
        <td>{{ $masaTahun }}&nbsp; Tahun</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- II. JENIS CUTI YANG DIAMBIL                                 --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="h">II.&nbsp;&nbsp;JENIS CUTI YANG DIAMBIL**</td>
    </tr>
    <tr>
        <td style="width:36%">1. Cuti Tahunan</td>
        <td style="width:14%; text-align:center">{{ $namaJenisCuti=='Cuti Tahunan' ? '✓' : '' }}</td>
        <td style="width:36%">2. Cuti Besar</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Besar' ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td>3. Cuti Sakit</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Sakit' ? '✓' : '' }}</td>
        <td>4. Cuti Melahirkan</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Melahirkan' ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td>5. Cuti Karena Alasan Penting</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti Karena Alasan Penting' ? '✓' : '' }}</td>
        <td>6. Cuti di Luar Tanggungan<br>&nbsp;&nbsp;&nbsp;Negara</td>
        <td style="text-align:center">{{ $namaJenisCuti=='Cuti di Luar Tanggungan Negara' ? '✓' : '' }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- III. ALASAN CUTI                                            --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td class="h">III.&nbsp;&nbsp;ALASAN CUTI</td>
    </tr>
    <tr>
        <td style="height:16px">{{ $leaveRequest->reason ?? '-' }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- IV. LAMANYA CUTI                                            --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="6" class="h">IV.&nbsp;&nbsp;&nbsp;LAMANYA CUTI</td>
    </tr>
    <tr>
        <td style="width:9%">Selama</td>
        <td style="width:22%">{{ $days }}  ({{ $dayText }} ) hari</td>
        <td style="width:13%">mulai tanggal</td>
        <td style="width:23%; text-align:center">{{ $tglMulai }}</td>
        <td style="width:5%; text-align:center">s/d</td>
        <td style="text-align:center">{{ $tglSelesai }}</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- V. CATATAN CUTI                                             --}}
{{--                                                              --}}
{{-- 5 kolom:                                                    --}}
{{-- Col1(Tahun) | Col2(Sisa) | Col3(Keterangan) | Col4(Paraf) | Col5(Jenis cuti 2-6) --}}
{{-- Row header cuti tahunan: colspan=3 | Paraf | 2.CUTI BESAR  --}}
{{-- Row sub-header:  Tahun | Sisa | Keterangan | (kosong) | 3.CUTI SAKIT --}}
{{-- Row N-2:   ... | 4.CUTI MELAHIRKAN                         --}}
{{-- Row N-1:   ... | 5.CUTI KARENA ALASAN PENTING              --}}
{{-- Row N:     ... | 6.CUTI DI LUAR TANGGUNGAN NEGARA          --}}
{{-- ============================================================ --}}
<table>
    {{-- Baris 1: Judul V --}}
    <tr>
        <td colspan="5" class="h">V.&nbsp;&nbsp;&nbsp;CATATAN CUTI***</td>
    </tr>
    {{-- Baris 2: Subtitle --}}
    <tr>
        <td colspan="5" style="font-size:7.5pt">
            1.&nbsp;&nbsp;Cuti Tahunan Tahun &nbsp;{{ $cy - 3 }}
        </td>
    </tr>
    {{-- Baris 3: Header kolom --}}
    <tr>
        <td colspan="3" class="h" style="font-size:7.5pt; width:38%">1. CUTI TAHUNAN</td>
        <td class="h" style="font-size:7pt; text-align:center; width:12%; vertical-align:middle">
            Paraf<br>Petugas Cuti
        </td>
        <td class="h" style="font-size:7.5pt">2. CUTI BESAR</td>
    </tr>
    {{-- Baris 4: Sub-header kolom --}}
    <tr>
        <td class="h" style="font-size:7.5pt; width:14%">Tahun</td>
        <td class="h" style="font-size:7.5pt; width:10%">Sisa</td>
        <td class="h" style="font-size:7.5pt; width:14%">Keterangan</td>
        <td style="font-size:7.5pt; width:12%"></td>
        <td style="font-size:7.5pt">3. CUTI SAKIT</td>
    </tr>
    {{-- Baris 5: N-2 --}}
    <tr>
        <td style="font-size:7.5pt">N-2.&nbsp;{{ $cy - 2 }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN2)->remaining_days ?? '0' }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN2)->used_days ?? '0' }}</td>
        <td style="font-size:7.5pt"></td>
        <td style="font-size:7.5pt">4. CUTI MELAHIRKAN</td>
    </tr>
    {{-- Baris 6: N-1 --}}
    <tr>
        <td style="font-size:7.5pt">N-1.&nbsp;{{ $cy - 1 }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN1)->remaining_days ?? '' }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN1)->used_days ?? '' }}</td>
        <td style="font-size:7.5pt"></td>
        <td style="font-size:7.5pt">5. CUTI KARENA ALASAN PENTING</td>
    </tr>
    {{-- Baris 7: N (tahun ini) --}}
    <tr>
        <td style="font-size:7.5pt">N.&nbsp;&nbsp;{{ $cy }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN)->remaining_days ?? '' }}</td>
        <td style="font-size:7.5pt; text-align:center">{{ optional($balN)->used_days ?? '' }}</td>
        <td style="font-size:7.5pt"></td>
        <td style="font-size:7.5pt">6. CUTI DI LUAR TANGGUNGAN<br>&nbsp;&nbsp;&nbsp;NEGARA</td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- VI. ALAMAT SELAMA MENJALANKAN CUTI                          --}}
{{--                                                              --}}
{{-- Layout:                                                      --}}
{{-- | Header (colspan=2)                              |          --}}
{{-- | Baris atas: (kosong) | TELP | nomor             |          --}}
{{-- | Baris bawah: alamat (kiri lebar) | Hormat Saya+sign+nama  --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="3" class="h">VI.&nbsp;&nbsp;ALAMAT SELAMA MENJALANKAN CUTI</td>
    </tr>
    <tr>
        {{-- Kiri: kosong di atas, alamat di bawah --}}
        <td style="width:55%; height:80px; vertical-align:top; padding:3px 6px">
            {{ $address }}
        </td>
        {{-- Kanan: TELP + Hormat Saya --}}
        <td style="width:13%; vertical-align:top; padding:2px 4px; font-size:7.8pt">
            TELP
        </td>
        <td style="width:32%; vertical-align:top; padding:2px 4px; font-size:7.8pt">
            {{ $phone }}<br>
            Hormat Saya,<br><br>
            @if(optional($emp)->signature_path)
                <img src="{{ asset('storage/' . $emp->signature_path) }}"
                     style="max-height:36px; max-width:100px; display:block; margin:2px 0"
                     alt="TTD">
            @else
                <div style="height:36px"></div>
            @endif
            <strong>{{ optional($empUser)->nama ?? '-' }}</strong><br>
            NIP. {{ optional($empUser)->nip ?? '-' }}
        </td>
    </tr>
</table>

{{-- ============================================================ --}}
{{-- VII. PERTIMBANGAN ATASAN LANGSUNG                           --}}
{{--                                                              --}}
{{-- Baris 1: header                                             --}}
{{-- Baris 2: DISETUJUI | PERUBAHAN | DITANGGUHKAN | TDK DISETUJUI --}}
{{-- Baris 3: area kosong + tanda tangan (kanan)                 --}}
{{-- ============================================================ --}}
<table>
    <tr>
        <td colspan="4" class="h">VII.&nbsp;&nbsp;PERTIMBANGAN ATASAN LANGSUNG**</td>
    </tr>
    <tr>
        <td style="width:25%">DISETUJUI{{ $stL=='disetujui' ? '  ✓' : '' }}</td>
        <td style="width:25%">PERUBAHAN****</td>
        <td style="width:25%">DITANGGUHKAN****{{ $stL=='ditangguhkan' ? '  ✓' : '' }}</td>
        <td style="width:25%">TIDAK DISETUJUI****{{ $stL=='ditolak' ? '  ✓' : '' }}</td>
    </tr>
    <tr>
        <td colspan="4" style="height:80px; padding:4px 8px; vertical-align:bottom">
            <div style="text-align:right">
                KETUA<br><br>
                @if(optional($aLEmp)->signature_path)
                    <img src="{{ asset('storage/' . $aLEmp->signature_path) }}"
                         style="max-height:36px; max-width:120px; display:block; margin-left:auto; margin-bottom:2px"
                         alt="TTD">
                @else
                    <div style="height:36px"></div>
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
        <td colspan="4" class="h">VIII.&nbsp;&nbsp;KEPUTUSAN PEJABAT &nbsp;YANG BERWENANG MEMBERIKAN CUTI**</td>
    </tr>
    <tr>
        <td style="width:25%">DISETUJUI{{ $stFinal=='disetujui' ? '  ✓' : '' }}</td>
        <td style="width:25%">PERUBAHAN****</td>
        <td style="width:25%">DITANGGUHKAN**{{ $stFinal=='ditangguhkan' ? '  ✓' : '' }}</td>
        <td style="width:25%">TIDAK DISETUJUI****{{ $stFinal=='ditolak' ? '  ✓' : '' }}</td>
    </tr>
    <tr>
        <td colspan="4" style="height:80px; padding:4px 8px; vertical-align:bottom">
            <div style="text-align:right">
                KETUA<br><br>
                @if(optional($aTLEmp)->signature_path)
                    <img src="{{ asset('storage/' . $aTLEmp->signature_path) }}"
                         style="max-height:36px; max-width:120px; display:block; margin-left:auto; margin-bottom:2px"
                         alt="TTD">
                @else
                    <div style="height:36px"></div>
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
    *&nbsp;Coret yang tidak perlu &nbsp;&nbsp;
    **&nbsp;Pilih salah satu &nbsp;&nbsp;
    ***&nbsp;Diisi oleh pejabat bidang kepegawaian sebelum PNS mengajukan cuti &nbsp;&nbsp;
    ****&nbsp;Diberi tanda centang oleh atasannya
</div>

</div>
<script>
    window.onload = function () { window.print(); }
</script>
</body>
</html>
