<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="leaveRequestsTable()">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Riwayat Cuti ✨</h1>
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
                            <input type="checkbox" class="form-checkbox" x-model="columns.jenisCuti" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Jenis Cuti</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.tanggalMulai" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Tanggal Mulai</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.tanggalSelesai" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Tanggal Selesai</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.totalHari" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Total Hari</span>
                        </label>
                        <label class="flex items-center py-1">
                            <input type="checkbox" class="form-checkbox" x-model="columns.status" checked>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium ml-2">Status</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Add leave request button -->
            <button class="btn bg-violet-500 hover:bg-violet-600 text-white" onclick="window.location='{{ route('pegawai.leave-requests.create') }}'">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1Z" />
                </svg>
                <span class="ml-2">Ajukan Cuti</span>
            </button>
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
                placeholder="Cari berdasarkan jenis cuti, status, atau tanggal..."
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
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Semua Pengajuan Cuti <span class="text-gray-400 dark:text-gray-500 font-medium" x-text="'(' + filteredLeaveRequests.length + ')'"></span></h2>
        </header>
        <div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                    <!-- Table header -->
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.jenisCuti">
                                <div class="font-semibold text-left">Jenis Cuti</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggalMulai">
                                <div class="font-semibold text-left">Tanggal Mulai</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggalSelesai">
                                <div class="font-semibold text-left">Tanggal Selesai</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.totalHari">
                                <div class="font-semibold text-left">Total Hari</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.status">
                                <div class="font-semibold text-left">Status</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Aksi</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm">
                        <template x-for="leave in filteredLeaveRequests" :key="leave.id">
                            <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.jenisCuti">
                                    <div class="font-medium text-gray-800 dark:text-gray-100" x-text="leave.leave_type_name"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggalMulai">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.start_date_formatted"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.tanggalSelesai">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.end_date_formatted"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.totalHari">
                                    <div class="text-gray-600 dark:text-gray-300" x-text="leave.total_days + ' hari'"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" x-show="columns.status">
                                    <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5" 
                                         :class="{
                                             'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400': leave.status === 'menunggu_atasan_langsung',
                                             'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400': leave.status === 'menunggu_atasan_tidak_langsung',
                                             'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400': leave.status === 'disetujui',
                                             'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400': leave.status === 'tidak_disetujui',
                                             'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400': leave.status === 'ditangguhkan',
                                             'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300': leave.status === 'perubahan'
                                         }"
                                         x-text="formatStatus(leave.status)">
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2">
                                        <a :href="'/pegawai/leave-requests/' + leave.id" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-blue-500" title="Lihat Detail">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M8 3.5c-3.5 0-6.3 2.3-7.5 5.5 1.2 3.2 4 5.5 7.5 5.5s6.3-2.3 7.5-5.5C14.3 5.8 11.5 3.5 8 3.5ZM8 12c-2 0-3.7-1.2-4.5-3 .8-1.8 2.5-3 4.5-3s3.7 1.2 4.5 3c-.8 1.8-2.5 3-4.5 3Zm0-5c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2Z"/>
                                            </svg>
                                        </a>
                                        <a :href="'/pegawai/leave-requests/' + leave.id + '/edit'" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-violet-500" title="Edit">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4ZM4.6 14H2v-2.6l6-6L10.6 8l-6 6ZM12 6.6 9.4 4 11 2.4 13.6 5 12 6.6Z" />
                                            </svg>
                                        </a>
                                        <button @click="deleteLeaveRequest(leave.id)" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-red-500" title="Hapus">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M5 7h2v6H5V7Zm4 0h2v6H9V7Zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1ZM6 2v1h4V2H6Zm7 3H3v9h10V5Z" />
                                            </svg>
                                        </button>
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
                                    <p class="text-sm">Tidak ditemukan pengajuan cuti yang sesuai dengan pencarian Anda.</p>
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
function leaveRequestsTable() {
    return {
        allLeaveRequests: @json($leaveRequestsData),
        filteredLeaveRequests: [],
        searchQuery: '',
        columns: {
            jenisCuti: true,
            tanggalMulai: true,
            tanggalSelesai: true,
            totalHari: true,
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
                    leave.leave_type_name.toLowerCase().includes(query) ||
                    leave.start_date_formatted.toLowerCase().includes(query) ||
                    leave.end_date_formatted.toLowerCase().includes(query) ||
                    leave.status.toLowerCase().includes(query) ||
                    this.formatStatus(leave.status).toLowerCase().includes(query)
                );
            });
        },
        
        formatStatus(status) {
            const statusMap = {
                'menunggu_atasan_langsung': 'Menunggu Atasan Langsung',
                'menunggu_atasan_tidak_langsung': 'Menunggu Atasan Tidak Langsung',
                'disetujui': 'Disetujui',
                'perubahan': 'Perubahan',
                'ditangguhkan': 'Ditangguhkan',
                'tidak_disetujui': 'Tidak Disetujui'
            };
            return statusMap[status] || status;
        },
        
        deleteLeaveRequest(leaveId) {
            if (confirm('Apakah Anda yakin ingin menghapus pengajuan cuti ini?')) {
                // Submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/pegawai/leave-requests/' + leaveId;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
</x-layouts.app>
