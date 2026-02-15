{{-- resources/views/pages/atasan_tidak_langsung/leave_history/index.blade.php --}}

@php
    $mappedLeaveRequests = $leaveRequests->map(function($lr) {
        return [
            'id' => $lr->id,
            'employee_name' => $lr->employee->user->nama ?? '-',
            'employee_nip' => $lr->employee->user->nip ?? '-',
            'leave_type' => $lr->leaveType->name ?? '-',
            'start_date' => $lr->start_date->format('d/m/Y'),
            'end_date' => $lr->end_date->format('d/m/Y'),
            'date_range' => $lr->start_date->format('d/m/Y') . ' - ' . $lr->end_date->format('d/m/Y'),
            'total_days' => $lr->total_days,
            'status' => $lr->status
        ];
    });
@endphp

<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="leaveHistoryTable()">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Riwayat Cuti Pegawai 📋</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Menampilkan riwayat cuti yang telah diproses</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            
            <!-- Filter Dropdown -->
            <div class="relative inline-flex" x-data="{ open: false }">
                <button
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300"
                    aria-haspopup="true"
                    @click.prevent="open = !open"
                    :aria-expanded="open"
                >
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M0 3a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1ZM3 8a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1ZM7 12a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2H7Z" />
                    </svg>
                    <span class="ml-2">Filter Kolom</span>
                </button>
                <div
                    class="origin-top-right z-10 absolute top-full left-0 right-auto min-w-[200px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1"
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-out duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak
                >
                    <div class="px-3 py-2">
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.nama" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Nama Pegawai</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.nip" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">NIP</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.jenis_cuti" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Jenis Cuti</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.tanggal" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Tanggal</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.total_hari" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Total Hari</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.status" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Status</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Search Bar -->
    <div class="mb-5">
        <div class="relative">
            <label for="leave-search" class="sr-only">Search</label>
            <input
                id="leave-search"
                class="form-input w-full pl-9 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 focus:border-violet-300 dark:focus:border-violet-600"
                type="search"
                placeholder="Cari berdasarkan nama pegawai, NIP, jenis cuti, atau status..."
                x-model="searchQuery"
                @input.debounce.300ms="filterLeaveRequests()"
            >
            <button class="absolute inset-0 right-auto group" type="button" aria-label="Search">
                <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 ml-3 mr-2" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7ZM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5Z" />
                    <path d="m13.314 11.9 2.393 2.393a.999.999 0 1 1-1.414 1.414L11.9 13.314a8.019 8.019 0 0 0 1.414-1.414Z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Cuti <span class="text-gray-400 dark:text-gray-500 font-medium" x-text="'(' + filteredLeaveRequests.length + ')'"></span></h2>
        </header>
        <div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                    <!-- Table header -->
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.nama">
                                <div class="font-semibold text-left">Nama Pegawai</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.nip">
                                <div class="font-semibold text-left">NIP</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.jenis_cuti">
                                <div class="font-semibold text-left">Jenis Cuti</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggal">
                                <div class="font-semibold text-left">Tanggal</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.total_hari">
                                <div class="font-semibold text-left">Total Hari</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.status">
                                <div class="font-semibold text-left">Status</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm">
                        <template x-for="leave in filteredLeaveRequests" :key="leave.id">
                            <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.nama">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-violet-100 dark:bg-violet-500/30 rounded-full mr-2">
                                            <span class="text-sm font-medium text-violet-600 dark:text-violet-400" x-text="leave.employee_name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="font-medium text-gray-800 dark:text-gray-100" x-text="leave.employee_name"></div>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.nip">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.employee_nip"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.jenis_cuti">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.leave_type"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggal">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.date_range"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.total_hari">
                                    <div class="text-center">
                                        <span class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300" x-text="leave.total_days + ' hari'"></span>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.status">
                                    <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5" 
                                         :class="{
                                             'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400': leave.status === 'disetujui',
                                             'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400': leave.status === 'ditolak',
                                             'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400': leave.status === 'ditangguhkan'
                                         }"
                                         x-text="formatStatus(leave.status)">
                                    </div>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty state -->
                        <tr x-show="filteredLeaveRequests.length === 0">
                            <td colspan="6" class="px-2 first:pl-5 last:pr-5 py-8">
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <svg class="inline-block w-16 h-16 mb-4 fill-current opacity-20" viewBox="0 0 64 64">
                                        <circle cx="32" cy="32" r="32"/>
                                        <path d="M32 48c8.8 0 16-7.2 16-16S40.8 16 32 16s-16 7.2-16 16 7.2 16 16 16zm0-28c6.6 0 12 5.4 12 12s-5.4 12-12 12-12-5.4-12-12 5.4-12 12-12z"/>
                                        <path d="M32 40c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm0-12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z"/>
                                    </svg>
                                    <p class="font-medium text-lg mb-1">Tidak ada data</p>
                                    <p class="text-sm">Tidak ditemukan riwayat cuti yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<script>
function leaveHistoryTable() {
    return {
        allLeaveRequests: @json($mappedLeaveRequests),
        filteredLeaveRequests: [],
        searchQuery: '',
        columns: {
            nama: true,
            nip: true,
            jenis_cuti: true,
            tanggal: true,
            total_hari: true,
            status: true
        },
        
        init() {
            this.filteredLeaveRequests = this.allLeaveRequests;
        },
        
        filterLeaveRequests() {
            const query = this.searchQuery.toLowerCase().trim();
            
            if (query === '') {
                this.filteredLeaveRequests = this.allLeaveRequests;
                return;
            }
            
            this.filteredLeaveRequests = this.allLeaveRequests.filter(leave => {
                return (
                    leave.employee_name.toLowerCase().includes(query) ||
                    leave.employee_nip.toLowerCase().includes(query) ||
                    leave.leave_type.toLowerCase().includes(query) ||
                    leave.status.toLowerCase().includes(query) ||
                    leave.date_range.toLowerCase().includes(query)
                );
            });
        },
        
        formatStatus(status) {
            const statusMap = {
                'disetujui': 'Disetujui',
                'ditolak': 'Ditolak',
                'ditangguhkan': 'Ditangguhkan'
            };
            return statusMap[status] || status;
        }
    }
}
</script>
</x-layouts.app>
