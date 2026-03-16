@php
    $statusClasses = match($leaveRequest->status) {
        'menunggu_atasan_langsung'       => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
        'menunggu_atasan_tidak_langsung' => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
        'disetujui'                      => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
        'perubahan'                      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'ditangguhkan'                   => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
        'tidak_disetujui'                => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
        default                          => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
    };
    $statusText = match($leaveRequest->status) {
        'menunggu_atasan_langsung'       => 'Menunggu Atasan Langsung',
        'menunggu_atasan_tidak_langsung' => 'Menunggu Atasan Tidak Langsung',
        'disetujui'                      => 'Disetujui',
        'perubahan'                      => 'Perubahan',
        'ditangguhkan'                   => 'Ditangguhkan',
        'tidak_disetujui'                => 'Tidak Disetujui',
        default                          => ucfirst($leaveRequest->status),
    };
@endphp

<div class="space-y-4">

    <!-- Informasi Pegawai -->
    <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-4">
        <h4 class="text-xs font-semibold text-violet-700 dark:text-violet-400 uppercase tracking-wider mb-3">Informasi Pegawai</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Nama</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->user->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">NIP</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->user->nip ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jabatan</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->jabatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Unit Kerja</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->employee->unit_kerja ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Cuti -->
    <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4">
        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Detail Cuti</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jenis Cuti</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->leaveType->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Hari</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->total_days }} hari</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->start_date->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $leaveRequest->end_date->format('d F Y') }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                <span class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-sm {{ $statusClasses }}">
                    {{ $statusText }}
                </span>
            </div>
        </div>
    </div>

    <!-- Alasan & Alamat -->
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alasan Cuti</p>
        <div class="text-sm text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/20 rounded-lg p-3">{{ $leaveRequest->reason }}</div>
    </div>
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alamat Selama Cuti</p>
        <div class="text-sm text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/20 rounded-lg p-3">{{ $leaveRequest->address_during_leave }}</div>
    </div>

    <!-- Riwayat Persetujuan -->
    @if($leaveRequest->approvals->count() > 0)
    <div>
        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Riwayat Persetujuan</h4>
        <div class="space-y-2">
            @foreach($leaveRequest->approvals as $approval)
            <div class="flex items-start gap-3 bg-gray-50 dark:bg-gray-900/20 p-3 rounded-lg">
                <div class="w-8 h-8 shrink-0 flex items-center justify-center bg-violet-100 dark:bg-violet-500/30 rounded-full text-sm font-medium text-violet-600 dark:text-violet-400">
                    {{ substr($approval->approver->nama ?? '-', 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $approval->approver->nama ?? '-' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $approval->approver->role ?? '-' }}</div>
                    @if($approval->note)
                    <div class="mt-1 text-xs text-gray-600 dark:text-gray-400"><strong>Catatan:</strong> {{ $approval->note }}</div>
                    @endif
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    {{ $approval->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
