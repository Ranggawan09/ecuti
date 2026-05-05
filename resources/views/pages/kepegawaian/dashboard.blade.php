<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Kepegawaian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, <strong>{{ auth()->user()->nama ?? '-' }}</strong></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kepegawaian.leave-requests.index') }}"
               class="btn bg-teal-500 hover:bg-teal-600 text-white">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Kelola Pengajuan
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">

        <!-- Total Pengajuan -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl p-5 text-white shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium opacity-80">Total Pengajuan</div>
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1">{{ $stats['total'] }}</div>
            <div class="text-xs opacity-70">Semua pengajuan cuti</div>
        </div>

        <!-- Total Pegawai -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Total Pegawai</div>
                <div class="w-8 h-8 bg-teal-100 dark:bg-teal-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['total_pegawai'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terdaftar</div>
        </div>

        <!-- Menunggu -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Menunggu</div>
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['menunggu'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Proses persetujuan</div>
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
            @if($belumDicetak > 0)
            <div class="text-xs text-amber-500 mt-1 font-medium">{{ $belumDicetak }} belum dicetak</div>
            @else
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total disetujui</div>
            @endif
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

        <!-- Persentase Disetujui -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tingkat Persetujuan</div>
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $approvalRate }}%</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total disetujui</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 border border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jumlah Cuti Per Bulan ({{ now()->year }})
            </h2>
            <div class="h-[250px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        <!-- Yearly Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 border border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                Jumlah Cuti 3 Tahun Terakhir
            </h2>
            <div class="h-[250px]">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Donut Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 flex flex-col items-center">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-1 self-start">Ringkasan Status Cuti</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4 self-start">Seluruh pengajuan semua pegawai</p>
            <canvas id="approvalDonutChart" class="max-w-[200px] max-h-[200px]"></canvas>
            <div class="flex flex-col gap-2 mt-4 text-xs w-full">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Menunggu ({{ $stats['menunggu'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Disetujui ({{ $stats['disetujui'] }})</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Tidak Disetujui ({{ $stats['tidak_disetujui'] }})</div>
            </div>
        </div>

        <!-- Form Nomor Surat -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-teal-100 dark:bg-teal-900/50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Surat Terbaru</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            @if($latestPrinted)
                                Berdasarkan surat terakhir dicetak:
                                <span class="font-medium text-gray-600 dark:text-gray-300">
                                    {{ optional($latestPrinted->employee)->user->nama ?? '-' }}
                                </span>
                                &mdash;
                                {{ $latestPrinted->printed_at ? \Carbon\Carbon::parse($latestPrinted->printed_at)->locale('id')->isoFormat('D MMM YYYY') : '-' }}
                            @else
                                Belum ada surat yang dicetak.
                            @endif
                        </p>
                    </div>
                </div>
            </header>
            <div class="p-5">
                {{-- Alert --}}
                <div id="saveAlert" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    {{-- No Urut --}}
                    <div>
                        <label for="noUrutInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            No Urut Surat
                        </label>
                        <input
                            type="number"
                            id="noUrutInput"
                            min="1"
                            value="{{ $latestPrinted->no_urut ?? '' }}"
                            placeholder="Contoh: 1"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                        >
                    </div>

                    {{-- Nomor Surat --}}
                    <div>
                        <label for="nomorSuratInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Nomor Surat
                        </label>
                        <input
                            type="text"
                            id="nomorSuratInput"
                            value="{{ $latestPrinted->nomor_surat ?? '' }}"
                            placeholder="Contoh: 1/KPN.W14.U5/KP5.3/III/2026"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="saveLetterNumber()"
                            id="saveBtn"
                            @if(!$latestPrinted) disabled @endif
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-teal-600 hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition">
                        <svg id="saveSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Simpan
                    </button>

                    @if($latestPrinted)
                    <a href="{{ route('kepegawaian.leave-requests.print', $latestPrinted->id) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Ulang
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Terbaru -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Pengajuan Cuti Terbaru</h2>
            <a href="{{ route('kepegawaian.leave-requests.index') }}" class="text-sm text-teal-500 hover:text-teal-600 font-medium">Lihat semua →</a>
        </header>
        <div class="p-4">
            @if($recentRequests->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Belum ada pengajuan cuti.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentRequests as $lr)
                    @php
                        $statusClass = match($lr->status) {
                            'menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung' => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
                            'disetujui'       => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
                            'ditangguhkan'    => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
                            'tidak_disetujui' => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
                            'perubahan'       => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
                            default           => 'bg-gray-100 dark:bg-gray-700 text-gray-500',
                        };
                        $statusText = match($lr->status) {
                            'menunggu_atasan_langsung'       => 'Menunggu AL',
                            'menunggu_atasan_tidak_langsung' => 'Menunggu ATL',
                            'disetujui'       => 'Disetujui',
                            'ditangguhkan'    => 'Ditangguhkan',
                            'tidak_disetujui' => 'Tidak Disetujui',
                            'perubahan'       => 'Perlu Perubahan',
                            default           => ucfirst($lr->status),
                        };
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="w-9 h-9 bg-teal-100 dark:bg-teal-500/20 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
    const menunggu  = {{ $stats['menunggu'] }};
    const disetujui = {{ $stats['disetujui'] }};
    const ditolak   = {{ $stats['tidak_disetujui'] }};
    const total = menunggu + disetujui + ditolak;

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
            labels: ['Menunggu', 'Disetujui', 'Tidak Disetujui'],
            datasets: [{
                data: [menunggu, disetujui, ditolak],
                backgroundColor: ['#fbbf24', '#34d399', '#f87171'],
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

// Monthly & Yearly Charts
(function() {
    const monthlyData = @json($monthlyData);
    const yearlyData  = @json($yearlyData);
    const yearlyLabels = @json($yearlyLabels);

    const mCtx = document.getElementById('monthlyChart');
    if (mCtx) {
        new Chart(mCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Cuti',
                    data: monthlyData,
                    backgroundColor: '#14b8a6',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const yCtx = document.getElementById('yearlyChart');
    if (yCtx) {
        new Chart(yCtx, {
            type: 'line',
            data: {
                labels: yearlyLabels,
                datasets: [{
                    label: 'Jumlah Cuti',
                    data: yearlyData,
                    borderColor: '#8b5cf6',
                    backgroundColor: '#8b5cf620',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#8b5cf6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
})();

// Letter number save
const leaveRequestId = {{ $latestPrinted->id ?? 'null' }};
const baseUrl  = "{{ url('/kepegawaian/leave-requests') }}";
const csrfToken = "{{ csrf_token() }}";

async function saveLetterNumber() {
    if (!leaveRequestId) return;

    const noUrut     = document.getElementById('noUrutInput').value;
    const nomorSurat = document.getElementById('nomorSuratInput').value;
    const spinner    = document.getElementById('saveSpinner');
    const btn        = document.getElementById('saveBtn');
    const alertEl    = document.getElementById('saveAlert');

    if (!noUrut || !nomorSurat) {
        showAlert('Harap isi semua field.', 'error');
        return;
    }

    spinner.classList.remove('hidden');
    btn.disabled = true;

    try {
        const res = await fetch(`${baseUrl}/${leaveRequestId}/letter-number`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ no_urut: noUrut, nomor_surat: nomorSurat })
        });

        const data = await res.json();

        if (data.success) {
            showAlert('Berhasil disimpan!', 'success');
        } else {
            throw new Error(data.message || 'Gagal menyimpan.');
        }
    } catch (err) {
        showAlert(err.message, 'error');
    } finally {
        spinner.classList.add('hidden');
        btn.disabled = false;
    }
}

function showAlert(message, type) {
    const alertEl = document.getElementById('saveAlert');
    alertEl.textContent = message;
    alertEl.className = type === 'success'
        ? 'mb-4 p-3 rounded-lg text-sm bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
        : 'mb-4 p-3 rounded-lg text-sm bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
    alertEl.classList.remove('hidden');
    if (type === 'success') {
        setTimeout(() => alertEl.classList.add('hidden'), 3000);
    }
}
</script>
</x-app-layout>
