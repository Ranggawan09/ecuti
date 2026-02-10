<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('pegawai.leave-requests.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
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
                                <span class="text-sm font-medium text-violet-600 dark:text-violet-400">{{ substr($leaveRequest->employee->user->nama ?? '-', 0, 1) }}</span>
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
                                <span class="text-sm font-medium text-violet-600 dark:text-violet-400">{{ substr($approval->approver->nama ?? '-', 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 dark:text-gray-100">{{ $approval->approver->nama ?? '-' }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $approval->approver->role ?? '-' }}</div>
                                <div class="mt-2">
                                    <span class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 text-xs {{ $approval->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400' }}">
                                        {{ $approval->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                                    </span>
                                </div>
                                @if($approval->notes)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Catatan:</strong> {{ $approval->notes }}
                                </div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $approval->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700/60">
                <a href="{{ route('pegawai.leave-requests.edit', $leaveRequest) }}" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4ZM4.6 14H2v-2.6l6-6L10.6 8l-6 6ZM12 6.6 9.4 4 11 2.4 13.6 5 12 6.6Z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('pegawai.leave-requests.destroy', $leaveRequest) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data cuti ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">
                        <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M5 7h2v6H5V7Zm4 0h2v6H9V7Zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1ZM6 2v1h4V2H6Zm7 3H3v9h10V5Z" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
</x-layouts.app>
