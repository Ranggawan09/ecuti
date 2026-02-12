{{-- resources/views/pages/atasan_langsung/approvals/index.blade.php --}}

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
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="leaveRequestsTable()">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Cuti Pegawai ✨</h1>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            
            <!-- Export Dropdown -->
            <div class="relative inline-flex" x-data="{ open: false }">
                <button
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300"
                    aria-haspopup="true"
                    @click.prevent="open = !open"
                    :aria-expanded="open"
                >
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm0 12H4V8h4v4Zm4 0H8V8h4v4Zm0-6H4V4h8v2Z" />
                    </svg>
                    <span class="ml-2">Export</span>
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
                    <ul>
                        <li>
                            <a class="font-medium text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 flex items-center py-1 px-3" href="#" @click.prevent="alert('Export Excel coming soon')">
                                <svg class="w-4 h-4 fill-current text-green-500 shrink-0 mr-2" viewBox="0 0 16 16">
                                    <path d="M9 7h6v2H9V7Zm0 4h6v2H9v-2Zm-9 0h6v2H0v-2Zm0-4h6v2H0V7Zm0-4h6v2H0V3Zm9 0h6v2H9V3Z" />
                                </svg>
                                <span>Export to Excel</span>
                            </a>
                        </li>
                        <li>
                            <a class="font-medium text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 flex items-center py-1 px-3" href="#" @click.prevent="alert('Export PDF coming soon')">
                                <svg class="w-4 h-4 fill-current text-red-500 shrink-0 mr-2" viewBox="0 0 16 16">
                                    <path d="M9 7h6v2H9V7Zm0 4h6v2H9v-2Zm-9 0h6v2H0v-2Zm0-4h6v2H0V7Zm0-4h6v2H0V3Zm9 0h6v2H9V3Z" />
                                </svg>
                                <span>Export to PDF</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

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
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Semua Data Cuti <span class="text-gray-400 dark:text-gray-500 font-medium" x-text="'(' + filteredLeaveRequests.length + ')'"></span></h2>
        </header>
        <div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                    <!-- Table header -->
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="flex items-center">
                                    <label class="inline-flex">
                                        <span class="sr-only">Select all</span>
                                        <input class="form-checkbox" type="checkbox" x-model="selectAll" @click="toggleSelectAll()">
                                    </label>
                                </div>
                            </th>
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
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Aksi</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm">
                        <template x-for="leave in filteredLeaveRequests" :key="leave.id">
                            <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="flex items-center">
                                        <label class="inline-flex">
                                            <span class="sr-only">Select</span>
                                            <input class="form-checkbox" type="checkbox" :value="leave.id" x-model="selected">
                                        </label>
                                    </div>
                                </td>
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
                                             'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300': leave.status === 'draft',
                                             'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400': leave.status === 'menunggu_atasan_langsung',
                                             'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400': leave.status === 'menunggu_atasan_tidak_langsung',
                                             'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400': leave.status === 'disetujui',
                                             'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400': leave.status === 'ditolak',
                                             'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400': leave.status === 'ditangguhkan'
                                         }"
                                         x-text="formatStatus(leave.status)">
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2">
                                        <button @click="showDetail(leave.id)" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-blue-500" title="View">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M8 2c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6 2.7-6 6-6Zm0-2C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Z" />
                                                <circle cx="8" cy="8" r="3" />
                                            </svg>
                                        </button>
                                        <button 
                                            x-show="leave.status === 'menunggu_atasan_langsung'"
                                            @click="showApprovalModal(leave.id, leave.employee_name)" 
                                            class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-violet-500" 
                                            title="Proses">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm3.7 6.7L7.4 11c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3L3.3 8.3c-.4-.4-.4-1 0-1.4.4-.4 1-.4 1.4 0L6.7 9l3.6-3.6c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.3Z" />
                                            </svg>
                                        </button>
                                        <button 
                                            x-show="leave.status !== 'menunggu_atasan_langsung'"
                                            disabled
                                            class="btn-sm bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-700/60 text-gray-400 dark:text-gray-500 cursor-not-allowed" 
                                            title="Sudah Diproses">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M11 0c.6 0 1 .4 1 1v3c.6 0 1 .4 1 1v8c0 .6-.4 1-1 1H4c-.6 0-1-.4-1-1V5c0-.6.4-1 1-1V1c0-.6.4-1 1-1h6ZM9 2H7v2h2V2ZM7 9.4 5.6 8c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l2 2c.2.2.4.3.7.3.3 0 .5-.1.7-.3l4-4c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L7 9.4Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty state -->
                        <tr x-show="filteredLeaveRequests.length === 0">
                            <td colspan="8" class="px-2 first:pl-5 last:pr-5 py-8">
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <svg class="inline-block w-16 h-16 mb-4 fill-current opacity-20" viewBox="0 0 64 64">
                                        <circle cx="32" cy="32" r="32"/>
                                        <path d="M32 48c8.8 0 16-7.2 16-16S40.8 16 32 16s-16 7.2-16 16 7.2 16 16 16zm0-28c6.6 0 12 5.4 12 12s-5.4 12-12 12-12-5.4-12-12 5.4-12 12-12z"/>
                                        <path d="M32 40c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm0-12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z"/>
                                    </svg>
                                    <p class="font-medium text-lg mb-1">Tidak ada data</p>
                                    <p class="text-sm">Tidak ditemukan data cuti yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <nav class="mb-4 sm:mb-0 sm:order-1" role="navigation" aria-label="Navigation">
                <ul class="flex justify-center">
                    <li class="ml-3 first:ml-0">
                        <a class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 text-gray-300 dark:text-gray-600" href="#" disabled>&lt;- Previous</a>
                    </li>
                    <li class="ml-3 first:ml-0">
                        <a class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-violet-500" href="#">Next -&gt;</a>
                    </li>
                </ul>
            </nav>
            <div class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                Showing <span class="font-medium text-gray-600 dark:text-gray-300" x-text="filteredLeaveRequests.length"></span> of <span class="font-medium text-gray-600 dark:text-gray-300" x-text="allLeaveRequests.length"></span> results
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeDetailModal()">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Detail Permohonan Cuti
                    </h3>
                    <button @click="closeDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </button>
                </div>
                
                <div id="detailContent" class="text-gray-700 dark:text-gray-300">
                    <div class="text-center py-5">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-violet-600"></div>
                        <p class="mt-2 text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approval -->
    <div id="approvalModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeApprovalModal()">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Proses Persetujuan Cuti
                    </h3>
                    <button @click="closeApprovalModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </button>
                </div>
                
                <input type="hidden" id="cuti_id">
                
                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Pegawai:</strong> <span id="nama_pegawai"></span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Keputusan</label>
                    <div class="space-y-2">
                        <button type="button" 
                                class="w-full decision-btn px-4 py-3 bg-emerald-50 border-2 border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-400 transition-all" 
                                data-decision="approve">
                            <svg class="inline-block w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Setujui Permohonan Cuti
                        </button>
                        <button type="button" 
                                class="w-full decision-btn px-4 py-3 bg-red-50 border-2 border-red-200 text-red-700 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:border-red-700 dark:text-red-400 transition-all" 
                                data-decision="reject">
                            <svg class="inline-block w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Tolak Permohonan Cuti
                        </button>
                    </div>
                </div>

                <!-- Form untuk Approval -->
                <div id="approveForm" style="display: none;">
                    <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <p class="text-sm text-emerald-800 dark:text-emerald-200">Anda akan menyetujui permohonan cuti ini.</p>
                    </div>
                    <div class="mb-4">
                        <label for="catatan_approve" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan (Opsional)</label>
                        <textarea id="catatan_approve" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>

                <!-- Form untuk Rejection -->
                <div id="rejectForm" style="display: none;">
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <p class="text-sm text-red-800 dark:text-red-200">Anda akan menolak permohonan cuti ini.</p>
                    </div>
                    <div class="mb-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alasan_penolakan" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan agar pegawai dapat melakukan revisi..."></textarea>
                        <small class="text-gray-500 dark:text-gray-400 text-xs">Minimal 10 karakter. Alasan ini akan dikirim ke pegawai untuk revisi.</small>
                        <div id="alasan_error" class="hidden text-red-500 text-sm mt-1">Alasan penolakan harus diisi minimal 10 karakter</div>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button type="button" 
                            @click="closeApprovalModal()" 
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="button" 
                            id="submitApprovalBtn" 
                            style="display: none;" 
                            class="flex-1 px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                        Kirim Keputusan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function leaveRequestsTable() {
    return {
        allLeaveRequests: @json($mappedLeaveRequests),
        filteredLeaveRequests: [],
        searchQuery: '',
        selected: [],
        selectAll: false,
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
        
        toggleSelectAll() {
            if (this.selectAll) {
                this.selected = this.filteredLeaveRequests.map(leave => leave.id);
            } else {
                this.selected = [];
            }
        },
        
        formatStatus(status) {
            const statusMap = {
                'draft': 'Draft',
                'menunggu_atasan_langsung': 'Menunggu Atasan Langsung',
                'menunggu_atasan_tidak_langsung': 'Menunggu Atasan Tidak Langsung',
                'disetujui': 'Disetujui',
                'ditolak': 'Ditolak',
                'ditangguhkan': 'Ditangguhkan'
            };
            return statusMap[status] || status;
        },
        
        showDetail(leaveId) {
            document.getElementById('detailModal').classList.remove('hidden');
            
            fetch(`/atasan-langsung/approvals/${leaveId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('detailContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('detailContent').innerHTML = '<div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg"><p class="text-red-600 dark:text-red-400">Gagal memuat detail cuti</p></div>';
                });
        },
        
        closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        },
        
        showApprovalModal(leaveId, employeeName) {
            document.getElementById('cuti_id').value = leaveId;
            document.getElementById('nama_pegawai').textContent = employeeName;
            document.getElementById('approvalModal').classList.remove('hidden');
            
            // Reset form
            document.querySelectorAll('.decision-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('approveForm').style.display = 'none';
            document.getElementById('rejectForm').style.display = 'none';
            document.getElementById('submitApprovalBtn').style.display = 'none';
            document.getElementById('catatan_approve').value = '';
            document.getElementById('alasan_penolakan').value = '';
            document.getElementById('alasan_error').classList.add('hidden');
        },
        
        closeApprovalModal() {
            document.getElementById('approvalModal').classList.add('hidden');
        }
    }
}

// Decision buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.decision-btn')) {
        const btn = e.target.closest('.decision-btn');
        const decision = btn.dataset.decision;
        
        document.querySelectorAll('.decision-btn').forEach(b => b.classList.remove('active', 'ring-2', 'ring-offset-2'));
        btn.classList.add('active', 'ring-2', 'ring-offset-2');
        
        if (decision === 'approve') {
            btn.classList.add('ring-emerald-500');
            document.getElementById('approveForm').style.display = 'block';
            document.getElementById('rejectForm').style.display = 'none';
        } else {
            btn.classList.add('ring-red-500');
            document.getElementById('rejectForm').style.display = 'block';
            document.getElementById('approveForm').style.display = 'none';
        }
        
        document.getElementById('submitApprovalBtn').style.display = 'block';
        document.getElementById('submitApprovalBtn').dataset.decision = decision;
    }
});

// Submit approval
document.addEventListener('click', function(e) {
    if (e.target.id === 'submitApprovalBtn') {
        const cutiId = document.getElementById('cuti_id').value;
        const decision = e.target.dataset.decision;
        
        if (decision === 'reject') {
            const alasan = document.getElementById('alasan_penolakan').value.trim();
            if (alasan.length < 10) {
                document.getElementById('alasan_error').classList.remove('hidden');
                return;
            }
            document.getElementById('alasan_error').classList.add('hidden');
        }
        
        const confirmMsg = decision === 'approve' 
            ? 'Apakah Anda yakin ingin menyetujui permohonan cuti ini?' 
            : 'Apakah Anda yakin ingin menolak permohonan cuti ini?';
        
        if (!confirm(confirmMsg)) return;
        
        const url = decision === 'approve'
            ? `/atasan-langsung/approvals/${cutiId}/approve`
            : `/atasan-langsung/approvals/${cutiId}/reject`;
        
        const data = {
            _token: '{{ csrf_token() }}'
        };
        
        if (decision === 'approve') {
            data.catatan = document.getElementById('catatan_approve').value;
        } else {
            data.alasan_penolakan = document.getElementById('alasan_penolakan').value;
        }
        
        e.target.disabled = true;
        e.target.textContent = 'Memproses...';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('approvalModal').classList.add('hidden');
                alert(data.message);
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan saat memproses');
            e.target.disabled = false;
            e.target.textContent = 'Kirim Keputusan';
        });
    }
});
</script>
</x-layouts.app>