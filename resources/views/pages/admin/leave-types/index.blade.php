<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="leaveTypesTable()">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Master Jenis Cuti ✨</h1>
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
                            <a class="font-medium text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 flex items-center py-1 px-3" href="{{ route('admin.leave-types.export', ['format' => 'excel']) }}" @click="open = false">
                                <svg class="w-4 h-4 fill-current text-green-500 shrink-0 mr-2" viewBox="0 0 16 16">
                                    <path d="M9 7h6v2H9V7Zm0 4h6v2H9v-2Zm-9 0h6v2H0v-2Zm0-4h6v2H0V7Zm0-4h6v2H0V3Zm9 0h6v2H9V3Z" />
                                </svg>
                                <span>Export to Excel</span>
                            </a>
                        </li>
                        <li>
                            <a class="font-medium text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 flex items-center py-1 px-3" href="{{ route('admin.leave-types.export', ['format' => 'pdf']) }}" @click="open = false">
                                <svg class="w-4 h-4 fill-current text-red-500 shrink-0 mr-2" viewBox="0 0 16 16">
                                    <path d="M9 7h6v2H9V7Zm0 4h6v2H9v-2Zm-9 0h6v2H0v-2Zm0-4h6v2H0V7Zm0-4h6v2H0V3Zm9 0h6v2H9V3Z" />
                                </svg>
                                <span>Export to PDF</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tambah Jenis Cuti button -->
            <button class="btn bg-violet-500 hover:bg-violet-600 text-white" @click="openModal()">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1Z" />
                </svg>
                <span class="ml-2">Tambah Jenis Cuti</span>
            </button>
        </div>

    </div>

    <!-- Search Bar -->
    <div class="mb-5">
        <div class="relative">
            <label for="leave-type-search" class="sr-only">Search</label>
            <input
                id="leave-type-search"
                class="form-input w-full pl-9 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 focus:border-violet-300 dark:focus:border-violet-600"
                type="search"
                placeholder="Cari berdasarkan nama jenis cuti..."
                x-model="searchQuery"
                @input.debounce.300ms="filterLeaveTypes()"
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
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Semua Jenis Cuti <span class="text-gray-400 dark:text-gray-500 font-medium" x-text="'(' + filteredLeaveTypes.length + ')'"></span></h2>
        </header>
        <div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                    <!-- Table header -->
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="font-semibold text-left">#</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Nama Jenis Cuti</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Maks. Hari</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">Potong Saldo</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="font-semibold text-left">Aksi</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm">
                        <template x-for="(leaveType, index) in filteredLeaveTypes" :key="leaveType.id">
                            <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-gray-500 dark:text-gray-400" x-text="index + 1"></div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 shrink-0 flex items-center justify-center bg-violet-100 dark:bg-violet-500/30 rounded-lg mr-3">
                                            <svg class="fill-current text-violet-500 dark:text-violet-400" width="14" height="14" viewBox="0 0 16 16">
                                                <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                                <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                            </svg>
                                        </div>
                                        <div class="font-medium text-gray-800 dark:text-gray-100" x-text="leaveType.name"></div>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-gray-800 dark:text-gray-100 font-medium" x-text="leaveType.max_days ?? '-'"></span>
                                        <span class="text-gray-400 dark:text-gray-500 text-xs ml-1.5" x-show="leaveType.max_days">hari</span>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5"
                                         :class="leaveType.deduct_balance ? 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700/60 text-gray-500 dark:text-gray-400'"
                                         x-text="leaveType.deduct_balance ? 'Ya' : 'Tidak'">
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2">
                                        <button @click="openModal(leaveType)" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-violet-500" title="Edit">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4ZM4.6 14H2v-2.6l6-6L10.6 8l-6 6ZM12 6.6 9.4 4 11 2.4 13.6 5 12 6.6Z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteLeaveType(leaveType.id)" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-red-500" title="Delete">
                                            <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M5 7h2v6H5V7Zm4 0h2v6H9V7Zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1ZM6 2v1h4V2H6Zm7 3H3v9h10V5Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty state -->
                        <tr x-show="filteredLeaveTypes.length === 0">
                            <td colspan="5" class="px-2 first:pl-5 last:pr-5 py-8">
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <svg class="inline-block w-16 h-16 mb-4 fill-current opacity-20" viewBox="0 0 64 64">
                                        <circle cx="32" cy="32" r="32"/>
                                        <path d="M32 48c8.8 0 16-7.2 16-16S40.8 16 32 16s-16 7.2-16 16 7.2 16 16 16zm0-28c6.6 0 12 5.4 12 12s-5.4 12-12 12-12-5.4-12-12 5.4-12 12-12z"/>
                                        <path d="M32 40c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm0-12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z"/>
                                    </svg>
                                    <p class="font-medium text-lg mb-1">Tidak ada data</p>
                                    <p class="text-sm">Tidak ditemukan jenis cuti yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Showing count -->
    <div class="mt-4">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan <span class="font-medium text-gray-600 dark:text-gray-300" x-text="filteredLeaveTypes.length"></span> dari <span class="font-medium text-gray-600 dark:text-gray-300" x-text="allLeaveTypes.length"></span> jenis cuti
        </div>
    </div>

    <!-- ===== MODAL CREATE / EDIT ===== -->
    <div
        x-show="showModal"
        class="fixed inset-0 z-1000 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/70" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div
            class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            @click.outside="closeModal()"
        >
            <!-- Modal header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700/60">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100" x-text="editingLeaveType ? 'Edit Jenis Cuti' : 'Tambah Jenis Cuti'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M12.7 3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 5.2 4.7 1.9c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4L6.6 6.6 3.3 9.9c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3l3.3-3.3 3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L9.4 6.6l3.3-3.3Z" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-5">
                <!-- Error message -->
                <div x-show="formError" class="mb-4 px-3 py-2 rounded-lg bg-red-100 dark:bg-red-500/30 border border-red-200 dark:border-red-500/60">
                    <p class="text-sm text-red-600 dark:text-red-400" x-text="formError"></p>
                </div>

                <!-- Nama -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Jenis Cuti <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        x-model="form.name"
                        class="form-input w-full"
                        placeholder="contoh: Cuti Tahunan"
                        @input="formError = ''"
                    >
                </div>

                <!-- Max Days -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Maksimum Hari
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            x-model.number="form.max_days"
                            class="form-input w-full pr-16"
                            placeholder="0"
                            min="0"
                            @input="formError = ''"
                        >
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500">hari</span>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kosongkan jika tidak ada batas</p>
                </div>

                <!-- Deduct Balance -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Potong Saldo Cuti
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" class="sr-only" x-model="form.deduct_balance">
                                <div class="w-11 h-6 rounded-full transition-colors duration-200"
                                     :class="form.deduct_balance ? 'bg-violet-500' : 'bg-gray-300 dark:bg-gray-600'">
                                </div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                                     :class="form.deduct_balance ? 'translate-x-5' : 'translate-x-0'">
                                </div>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-300" x-text="form.deduct_balance ? 'Ya, potong saldo' : 'Tidak potong saldo'"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700/60">
                <button @click="closeModal()" class="btn bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 text-gray-600 dark:text-gray-300">
                    Batal
                </button>
                <button
                    @click="submitForm()"
                    class="btn bg-violet-500 hover:bg-violet-600 text-white"
                    :disabled="!form.name.trim()"
                    :class="!form.name.trim() ? 'opacity-50 cursor-not-allowed' : ''"
                >
                    <span x-text="editingLeaveType ? 'Simpan Perubahan' : 'Tambah Jenis Cuti'"></span>
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function leaveTypesTable() {
    return {
        allLeaveTypes: @json($leaveTypes),
        filteredLeaveTypes: @json($leaveTypes),
        searchQuery: '',

        // Modal state
        showModal: false,
        editingLeaveType: null,
        formError: '',
        form: {
            name: '',
            max_days: null,
            deduct_balance: true
        },

        // Search
        filterLeaveTypes() {
            const query = this.searchQuery.toLowerCase().trim();
            if (query === '') {
                this.filteredLeaveTypes = this.allLeaveTypes;
                return;
            }
            this.filteredLeaveTypes = this.allLeaveTypes.filter(item =>
                item.name.toLowerCase().includes(query)
            );
        },

        // Modal
        openModal(leaveType = null) {
            this.formError = '';
            if (leaveType) {
                this.editingLeaveType = leaveType;
                this.form = {
                    name: leaveType.name,
                    max_days: leaveType.max_days,
                    deduct_balance: !!leaveType.deduct_balance
                };
            } else {
                this.editingLeaveType = null;
                this.form = { name: '', max_days: null, deduct_balance: true };
            }
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingLeaveType = null;
            this.formError = '';
        },

        // Submit via axios
        async submitForm() {
            if (!this.form.name.trim()) {
                this.formError = 'Nama jenis cuti tidak boleh kosong.';
                return;
            }

            const payload = {
                name: this.form.name.trim(),
                max_days: this.form.max_days || null,
                deduct_balance: this.form.deduct_balance ? 1 : 0
            };

            try {
                if (this.editingLeaveType) {
                    // Update
                    await axios.put('/admin/leave-types/' + this.editingLeaveType.id, {
                        _token: '{{ csrf_token() }}',
                        ...payload
                    });
                    // Update item di array lokal
                    const idx = this.allLeaveTypes.findIndex(i => i.id === this.editingLeaveType.id);
                    if (idx !== -1) {
                        this.allLeaveTypes[idx] = { ...this.allLeaveTypes[idx], ...payload, deduct_balance: this.form.deduct_balance };
                    }
                } else {
                    // Create
                    const response = await axios.post('/admin/leave-types', {
                        _token: '{{ csrf_token() }}',
                        ...payload
                    });
                    // Tambah item baru ke array lokal
                    this.allLeaveTypes.push({
                        id: response.data.id,
                        ...payload,
                        deduct_balance: this.form.deduct_balance
                    });
                }

                this.closeModal();
                this.filterLeaveTypes(); // refresh filter
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    this.formError = Object.values(errors)[0][0];
                } else {
                    this.formError = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            }
        },

        // Delete
        async deleteLeaveType(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus jenis cuti ini?')) return;

            try {
                await axios.delete('/admin/leave-types/' + id, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                this.allLeaveTypes = this.allLeaveTypes.filter(i => i.id !== id);
                this.filterLeaveTypes();
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus. Silakan coba lagi.');
            }
        }
    }
}
</script>
</x-layouts.app>