<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    @php
        $currentYear = (int) now()->year;
        $yearsToFetch = [$currentYear, $currentYear - 1, $currentYear - 2];
        $employee = auth()->user()->employee;
        $leaveBalances = $employee
            ? $employee->leaveBalances()->whereIn('year', $yearsToFetch)->orderBy('year', 'desc')->get()
            : collect();

        // Current year balance
        $currentBalance = $leaveBalances->firstWhere('year', $currentYear);
        $totalAvailable = $leaveBalances->sum(fn($b) => max(0, $b->remaining_days) + max(0, $b->carried_over_days));

        // Leave request counts
        $allLeaveRequests = $employee ? $employee->leaveRequests()->latest()->limit(5)->get() : collect();
        $pendingCount  = $employee ? $employee->leaveRequests()->whereIn('status', ['menunggu_atasan_langsung','menunggu_atasan_tidak_langsung'])->count() : 0;
        $approvedCount = $employee ? $employee->leaveRequests()->where('status', 'disetujui')->count() : 0;
        $rejectedCount = $employee ? $employee->leaveRequests()->where('status', 'tidak_disetujui')->count() : 0;
    @endphp

    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Pegawai</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, <strong>{{ auth()->user()->nama ?? '-' }}</strong> 👋</p>
        </div>
        <div>
            <a href="{{ route('pegawai.leave-requests.create') }}"
               class="btn bg-violet-500 hover:bg-violet-600 text-white">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                </svg>
                Ajukan Cuti
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Sisa Cuti -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-violet-500 to-violet-700 rounded-xl p-5 text-white shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium opacity-80">Total Sisa Cuti</div>
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1">{{ $totalAvailable }} <span class="text-lg font-normal opacity-80">hari</span></div>
            <div class="text-xs opacity-70">Dari tahun {{ $currentYear - 2 }} s/d {{ $currentYear }}</div>
        </div>

        <!-- Cuti Tahun Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Hak Cuti {{ $currentYear }}</div>
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $currentBalance->total_days ?? 0 }} <span class="text-sm font-normal text-gray-400">hari</span></div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terpakai: {{ $currentBalance->used_days ?? 0 }} hari</div>
        </div>

        <!-- Menunggu Persetujuan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Menunggu</div>
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $pendingCount }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pengajuan pending</div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Disetujui</div>
                <div class="w-8 h-8 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $approvedCount }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total disetujui</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Donut Chart: Current Year Balance -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 flex flex-col items-center">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-1 self-start">Porsi Cuti {{ $currentYear }}</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4 self-start">Perbandingan terpakai vs tersisa</p>
            <canvas id="leaveDonutChart" class="max-w-[200px] max-h-[200px]"></canvas>
            <div class="flex gap-4 mt-4 text-xs">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Terpakai ({{ $currentBalance->used_days ?? 0 }}h)</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Sisa ({{ $currentBalance->remaining_days ?? 0 }}h)</div>
                @if(($currentBalance->carried_over_days ?? 0) > 0)
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span> Tangguhan ({{ $currentBalance->carried_over_days }}h)</div>
                @endif
            </div>
        </div>

        <!-- Tabel Riwayat Saldo -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Sisa Cuti (3 Tahun Terakhir)</h2>
            </header>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-sm dark:text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="p-3 text-left">Tahun</th>
                                <th class="p-3 text-center">Hak Cuti</th>
                                <th class="p-3 text-center">Ditangguhkan</th>
                                <th class="p-3 text-center">Terpakai</th>
                                <th class="p-3 text-center">Sisa</th>
                                <th class="p-3 text-center">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($leaveBalances as $bal)
                            @php
                                $usedPct = $bal->total_days > 0 ? round(($bal->used_days / $bal->total_days) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="p-3">
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $bal->year }}</div>
                                    @if($bal->year == $currentYear)
                                        <span class="text-[10px] bg-violet-100 dark:bg-violet-500/30 text-violet-600 dark:text-violet-400 rounded-full px-2 py-0.5">Tahun Ini</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center font-medium">{{ $bal->total_days }}</td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex items-center justify-center text-orange-500 font-medium">{{ $bal->carried_over_days }}</span>
                                </td>
                                <td class="p-3 text-center text-red-500 font-medium">{{ $bal->used_days }}</td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex font-bold text-emerald-600 dark:text-emerald-400">{{ $bal->remaining_days }}</span>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $usedPct > 80 ? 'bg-red-400' : ($usedPct > 50 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                                 style="width: {{ $usedPct }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-400 w-8">{{ $usedPct }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Data sisa cuti belum tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Pengajuan Cuti Terbaru</h2>
            <a href="{{ route('pegawai.leave-requests.index') }}" class="text-sm text-violet-500 hover:text-violet-600 font-medium">Lihat semua →</a>
        </header>
        <div class="p-4">
            @if($allLeaveRequests->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Belum ada pengajuan cuti.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($allLeaveRequests as $lr)
                    @php
                        $statusClass = match($lr->status) {
                            'menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung' => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
                            'disetujui' => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
                            'ditangguhkan' => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
                            'tidak_disetujui' => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
                            'perubahan' => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
                            default => 'bg-gray-100 dark:bg-gray-700 text-gray-500',
                        };
                        $statusText = match($lr->status) {
                            'menunggu_atasan_langsung' => 'Menunggu AL',
                            'menunggu_atasan_tidak_langsung' => 'Menunggu ATL',
                            'disetujui' => 'Disetujui',
                            'ditangguhkan' => 'Ditangguhkan',
                            'tidak_disetujui' => 'Tidak Disetujui',
                            'perubahan' => 'Perlu Perubahan',
                            default => ucfirst($lr->status),
                        };
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="w-9 h-9 bg-violet-100 dark:bg-violet-500/20 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $lr->leaveType->name ?? '-' }}</div>
                            <div class="text-xs text-gray-400">
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
    const used   = {{ $currentBalance->used_days ?? 0 }};
    const remain = {{ $currentBalance->remaining_days ?? 0 }};
    const carried = {{ $currentBalance->carried_over_days ?? 0 }};

    const labels = ['Terpakai', 'Sisa'];
    const data   = [used, remain];
    const colors = ['#f87171', '#34d399'];

    if (carried > 0) {
        labels.push('Ditangguhkan');
        data.push(carried);
        colors.push('#fb923c');
    }

    const ctx = document.getElementById('leaveDonutChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
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
                        label: ctx => ` ${ctx.formattedValue} hari`
                    }
                }
            }
        }
    });
})();
</script>
</x-app-layout>
