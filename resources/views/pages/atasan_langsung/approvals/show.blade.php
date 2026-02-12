{{-- resources/views/pages/atasan_langsung/approvals/show.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Cuti Pegawai</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('atasan-langsung.approvals.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M6.6 13.4L1.2 8l5.4-5.4 1.4 1.4L4.4 7.6h10.4v2H4.4l3.6 3.6-1.4 1.4z" />
                </svg>
                <span>Kembali</span>
            </a>
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Detail Cuti Pegawai</h1>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Cuti</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Employee Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase mb-3">Informasi Pegawai</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Pegawai</label>
                        <div class="flex items-center">
                            <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-violet-100 dark:bg-violet-500/30 rounded-full mr-3">
                                <span class="text-sm font-medium text-violet-600 dark:text-violet-400">{{ strtoupper(substr($leaveRequest->employee->user->nama ?? '-', 0, 1)) }}</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-100 font-medium">{{ $leaveRequest->employee->user->nama ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">NIP</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->user->nip ?? '-' }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Jabatan</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->jabatan ?? '-' }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Unit Kerja</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->unit_kerja ?? '-' }}</div>
                    </div>
                </div>

                <!-- Leave Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase mb-3">Detail Cuti</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Jenis Cuti</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->leaveType->name ?? '-' }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal Mulai</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->start_date->format('d F Y') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal Selesai</label>
                        <div class="text-gray-800 dark:text-gray-100">{{ $leaveRequest->end_date->format('d F Y') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Hari</label>
                        <div>
                            <span class="inline-flex font-medium rounded-full text-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $leaveRequest->total_days }} hari
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <div>
                            @php
                                $statusClasses = match($leaveRequest->status) {
                                    'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                    'menunggu_atasan_langsung' => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
                                    'menunggu_atasan_tidak_langsung' => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
                                    'disetujui' => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
                                    'ditolak' => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
                                    'ditangguhkan' => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'
                                };
                                $statusText = match($leaveRequest->status) {
                                    'draft' => 'Draft',
                                    'menunggu_atasan_langsung' => 'Menunggu Atasan Langsung',
                                    'menunggu_atasan_tidak_langsung' => 'Menunggu Atasan Tidak Langsung',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'ditangguhkan' => 'Ditangguhkan',
                                    default => ucfirst($leaveRequest->status)
                                };
                            @endphp
                            <span class="inline-flex font-medium rounded-full text-center px-3 py-1 {{ $statusClasses }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Full Width Sections -->
                <div class="md:col-span-2">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Alasan Cuti</label>
                        <div class="text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg">
                            {{ $leaveRequest->reason }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Alamat Selama Cuti</label>
                        <div class="text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg">
                            {{ $leaveRequest->address_during_leave }}
                        </div>
                    </div>
                </div>

                <!-- Approval History -->
                @if($leaveRequest->approvals->count() > 0)
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase mb-3">Riwayat Persetujuan</h3>
                    <div class="space-y-3">
                        @foreach($leaveRequest->approvals as $approval)
                        <div class="flex items-start gap-3 bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg">
                            <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-violet-100 dark:bg-violet-500/30 rounded-full">
                                <span class="text-sm font-medium text-violet-600 dark:text-violet-400">{{ strtoupper(substr($approval->approver->nama ?? '-', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 dark:text-gray-100">{{ $approval->approver->nama ?? '-' }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $approval->approver->role ?? '-' }}</div>
                                <div class="mt-2">
                                    <span class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-xs {{ $approval->status === 'disetujui' ? 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400' }}">
                                        {{ $approval->status === 'disetujui' ? 'Disetujui' : 'Ditolak' }}
                                    </span>
                                </div>
                                @if($approval->note)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Catatan:</strong> {{ $approval->note }}
                                </div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : ($approval->created_at ? $approval->created_at->format('d/m/Y H:i') : '-') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="decision"]');
    const catatanSection = document.getElementById('catatanSection');
    const alasanSection = document.getElementById('alasanSection');
    const alasanInput = document.getElementById('alasan_penolakan');
    const form = document.getElementById('approvalForm');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'approve') {
                catatanSection.classList.remove('hidden');
                alasanSection.classList.add('hidden');
                alasanInput.removeAttribute('required');
                form.action = "{{ route('atasan-langsung.approvals.approve', $leaveRequest) }}";
            } else {
                alasanSection.classList.remove('hidden');
                catatanSection.classList.add('hidden');
                alasanInput.setAttribute('required', 'required');
                form.action = "{{ route('atasan-langsung.approvals.reject', $leaveRequest) }}";
            }
        });
    });

    form.addEventListener('submit', function(e) {
        const decision = document.querySelector('input[name="decision"]:checked');
        
        if (!decision) {
            e.preventDefault();
            alert('Silakan pilih keputusan terlebih dahulu');
            return;
        }

        if (decision.value === 'reject') {
            const alasan = alasanInput.value.trim();
            if (alasan.length < 10) {
                e.preventDefault();
                alert('Alasan penolakan harus diisi minimal 10 karakter');
                return;
            }
        }

        const confirmMsg = decision.value === 'approve' 
            ? 'Apakah Anda yakin ingin menyetujui permohonan cuti ini?' 
            : 'Apakah Anda yakin ingin menolak permohonan cuti ini?';
        
        if (!confirm(confirmMsg)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>