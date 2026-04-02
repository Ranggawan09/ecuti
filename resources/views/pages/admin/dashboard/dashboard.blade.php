<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Admin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, <strong>{{ auth()->user()->nama ?? '-' }}</strong></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.index') }}"
               class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Kelola Pengguna
            </a>
        </div>
    </div>

    <!-- Summary Cards Row 1 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Total Pengajuan -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl p-5 text-white shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium opacity-80">Total Pengajuan</div>
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1">{{ $stats['total_pengajuan'] }} <span class="text-lg font-normal opacity-80">pengajuan</span></div>
            <div class="text-xs opacity-70">Semua pengajuan cuti</div>
        </div>

        <!-- Total Pegawai -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Total Pegawai</div>
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['total_pegawai'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terdaftar di sistem</div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Disetujui</div>
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['disetujui'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total disetujui</div>
        </div>

        <!-- Tidak Disetujui -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tidak Disetujui</div>
                <div class="w-8 h-8 bg-red-100 dark:bg-red-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['tidak_disetujui'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total ditolak</div>
        </div>
    </div>

    <!-- Summary Cards Row 2 -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        <!-- Menunggu AL -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Menunggu AL</div>
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['menunggu_al'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menunggu atasan langsung</div>
        </div>

        <!-- Menunggu ATL -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Menunggu ATL</div>
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['menunggu_atl'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menunggu atasan tidak langsung</div>
        </div>

        <!-- Ditangguhkan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Ditangguhkan</div>
                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['ditangguhkan'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sedang ditangguhkan</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Donut Chart: Status Ringkasan -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 flex flex-col items-center">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-1 self-start">Ringkasan Status Cuti</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4 self-start">Perbandingan status semua pengajuan</p>
            <canvas id="approvalDonutChart" class="max-w-[200px] max-h-[200px]"></canvas>
            <div class="flex flex-col gap-2 mt-4 text-xs w-full">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Menunggu AL ({{ $stats['menunggu_al'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span> Menunggu ATL ({{ $stats['menunggu_atl'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Disetujui ({{ $stats['disetujui'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Tidak Disetujui ({{ $stats['tidak_disetujui'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span> Ditangguhkan ({{ $stats['ditangguhkan'] }})</div>
            </div>
        </div>

        <!-- Daftar Pegawai -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Pegawai</h2>
                <a href="{{ route('admin.employees.index') }}" class="text-xs bg-indigo-100 dark:bg-indigo-500/30 text-indigo-600 dark:text-indigo-400 rounded-full px-2 py-1 hover:bg-indigo-200 dark:hover:bg-indigo-500/50 transition-colors">
                    {{ $stats['total_pegawai'] }} pegawai →
                </a>
            </header>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-sm dark:text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">Jabatan</th>
                                <th class="p-3 text-left">Unit Kerja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($employees as $emp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center shrink-0">
                                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ strtoupper(substr($emp->user->nama ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $emp->user->nama ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-gray-600 dark:text-gray-400">{{ $emp->jabatan ?? '-' }}</td>
                                <td class="p-3 text-gray-600 dark:text-gray-400">{{ $emp->unit_kerja ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Belum ada pegawai terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Menunggu Persetujuan -->
    @if($pendingRequests->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Pengajuan Menunggu Persetujuan</h2>
            <span class="text-xs bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400 rounded-full px-2 py-1">{{ $pendingRequests->count() }} menunggu</span>
        </header>
        <div class="p-4">
            <div class="space-y-3">
                @foreach($pendingRequests as $lr)
                @php
                    $isPendingAL  = $lr->status === 'menunggu_atasan_langsung';
                    $badgeClass   = $isPendingAL
                        ? 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400'
                        : 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400';
                    $badgeText    = $isPendingAL ? 'Menunggu AL' : 'Menunggu ATL';
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20">
                    <div class="w-9 h-9 bg-amber-100 dark:bg-amber-500/20 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $lr->employee->user->nama ?? '-' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $lr->leaveType->name ?? '-' }} ·
                            {{ \Carbon\Carbon::parse($lr->start_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($lr->end_date)->format('d M Y') }}
                            · {{ $lr->total_days }} hari
                        </div>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $badgeClass }} shrink-0">{{ $badgeText }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Riwayat Pengajuan Terbaru -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Pengajuan Terbaru</h2>
        </header>
        <div class="p-4">
            @if($recentRequests->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Belum ada riwayat pengajuan.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentRequests as $lr)
                    @php
                        $statusClass = match($lr->status) {
                            'menunggu_atasan_langsung'       => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
                            'menunggu_atasan_tidak_langsung' => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
                            'disetujui'                      => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
                            'ditangguhkan'                   => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
                            'tidak_disetujui'                => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
                            'perubahan'                      => 'bg-purple-100 dark:bg-purple-500/30 text-purple-600 dark:text-purple-400',
                            default                          => 'bg-gray-100 dark:bg-gray-700 text-gray-500',
                        };
                        $statusText = match($lr->status) {
                            'menunggu_atasan_langsung'       => 'Menunggu AL',
                            'menunggu_atasan_tidak_langsung' => 'Menunggu ATL',
                            'disetujui'                      => 'Disetujui',
                            'ditangguhkan'                   => 'Ditangguhkan',
                            'tidak_disetujui'                => 'Tidak Disetujui',
                            'perubahan'                      => 'Perlu Perubahan',
                            default                          => ucfirst($lr->status),
                        };
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="w-9 h-9 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $lr->employee->user->nama ?? '-' }}</div>
                            <div class="text-xs text-gray-400">
                                {{ $lr->leaveType->name ?? '-' }} ·
                                {{ \Carbon\Carbon::parse($lr->start_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($lr->end_date)->format('d M Y') }}
                                · {{ $lr->total_days }} hari
                            </div>
                        </div>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
(function() {
    const menungguAL  = {{ $stats['menunggu_al'] }};
    const menungguATL = {{ $stats['menunggu_atl'] }};
    const disetujui   = {{ $stats['disetujui'] }};
    const ditolak     = {{ $stats['tidak_disetujui'] }};
    const ditangguhkan = {{ $stats['ditangguhkan'] }};

    const total = menungguAL + menungguATL + disetujui + ditolak + ditangguhkan;

    const ctx = document.getElementById('approvalDonutChart');
    if (!ctx) return;

    if (total === 0) {
        ctx.parentElement.innerHTML += '<p class="text-xs text-gray-400 dark:text-gray-500 mt-4">Belum ada data pengajuan.</p>';
        ctx.style.display = 'none';
        return;
    }

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu AL', 'Menunggu ATL', 'Disetujui', 'Tidak Disetujui', 'Ditangguhkan'],
            datasets: [{
                data: [menungguAL, menungguATL, disetujui, ditolak, ditangguhkan],
                backgroundColor: ['#fbbf24', '#60a5fa', '#34d399', '#f87171', '#fb923c'],
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.formattedValue} pengajuan`
                    }
                }
            }
        }
    });
})();
</script>
</x-app-layout>
