<x-layouts.app>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="quotaManager()">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <a href="{{ route('admin.leave-types.index') }}"
                    class="text-sm font-medium text-violet-500 hover:text-violet-600 flex items-center mb-2">
                    <svg class="fill-current mr-2" width="12" height="12" viewBox="0 0 12 12">
                        <path
                            d="M10 5H4.414l2.293-2.293a1 1 0 1 0-1.414-1.414l-4 4a1 1 0 0 0 0 1.414l4 4a1 1 0 0 0 1.414-1.414L4.414 7H10a1 1 0 1 0 0-2z" />
                    </svg>
                    <span>Kembali ke Master Jenis Cuti</span>
                </a>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Atur Jatah:
                    {{ $leaveType->name }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola jatah cuti pegawai tahunan</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button class="btn bg-violet-500 hover:bg-violet-600 text-white" @click="saveQuotas()"
                    :disabled="saving">
                    <svg x-show="!saving" class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0z" />
                    </svg>
                    <svg x-show="saving" class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="ml-2" x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                </button>
            </div>

        </div>

        <!-- Tabs -->
        <div class="relative mb-6 px-8 ">
            <div class="absolute bottom-0 w-full h-px bg-gray-200 dark:bg-gray-700/60" aria-hidden="true"></div>
            <ul
                class="relative text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 overflow-x-scroll no-scrollbar">
                <template x-for="yr in years" :key="yr">
                    <li
                        class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                        <button @click="activeYear = yr" class="block pb-3 whitespace-nowrap"
                            :class="activeYear === yr ? 'text-violet-500 border-b-2 border-violet-500' : 'text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'">
                            Tahun <span x-text="yr"></span>
                        </button>
                    </li>
                </template>
            </ul>
        </div>

        <!-- Search Bar -->
        <div class="mb-5">
            <div class="relative">
                <label for="employee-search" class="sr-only">Search</label>
                <input id="employee-search"
                    class="form-input w-full pl-9 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 focus:border-violet-300 dark:focus:border-violet-600"
                    type="search" placeholder="Cari nama pegawai atau NIP..." x-model="searchQuery">
                <button class="absolute inset-0 right-auto group" type="button" aria-label="Search">
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 ml-3 mr-2"
                        width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7ZM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5Z" />
                        <path
                            d="m13.314 11.9 2.393 2.393a.999.999 0 1 1-1.414 1.414L11.9 13.314a8.019 8.019 0 0 0 1.414-1.414Z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Pegawai <span
                        class="text-gray-400 dark:text-gray-500 font-medium"
                        x-text="'(' + filteredEmployees.length + ')'"></span></h2>
            </header>
            <div>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                        <thead
                            class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="font-semibold text-left">#</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Nama Pegawai / NIP</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Unit Kerja</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-center">Jatah Hari (Tahun <span
                                            x-text="activeYear"></span>)</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <template x-for="(employee, index) in filteredEmployees" :key="employee.id">
                                <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="text-gray-500 dark:text-gray-400" x-text="index + 1"></div>
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-gray-100"
                                                x-text="employee.user.nama"></div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500"
                                                x-text="employee.nip || '-'"></div>
                                        </div>
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="text-gray-600 dark:text-gray-300"
                                            x-text="employee.unit_kerja || '-'"></div>
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="flex justify-center">
                                            <div class="relative w-24">
                                                <input type="number" class="form-input w-full text-center"
                                                    x-model.number="quotas[activeYear][employee.id]" min="0">
                                                <div
                                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span class="text-xs text-gray-400">hari</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function quotaManager() {
            return {
                years: [],
                activeYear: null,
                employees: @json($employees),
                searchQuery: '',
                quotas: {}, // Structure: { year: { empId: days } }
                saving: false,

                init() {
                    const currentYear = {{ now()->year }};
                    this.years = [currentYear, currentYear - 1, currentYear - 2];
                    this.activeYear = currentYear;

                    // Initialize quotas object from data
                    this.years.forEach(yr => {
                        this.quotas[yr] = {};
                        this.employees.forEach(emp => {
                            const balance = emp.leave_balances.find(b => b.year == yr);
                            this.quotas[yr][emp.id] = balance ? balance.total_days : 0;
                        });
                    });
                },

                get filteredEmployees() {
                    if (!this.searchQuery) return this.employees;
                    const query = this.searchQuery.toLowerCase();
                    return this.employees.filter(emp =>
                        emp.user.nama.toLowerCase().includes(query) ||
                        (emp.nip && emp.nip.toLowerCase().includes(query))
                    );
                },

                async saveQuotas() {
                    this.saving = true;

                    // Prepare bulk payload for all years
                    const payload = [];
                    Object.keys(this.quotas).forEach(yr => {
                        const yearQuotas = this.quotas[yr];
                        Object.keys(yearQuotas).forEach(empId => {
                            payload.push({
                                year: yr,
                                employee_id: empId,
                                total_days: yearQuotas[empId]
                            });
                        });
                    });

                    try {
                        const response = await axios.post('{{ route('admin.leave-types.update-quotas', $leaveType) }}', {
                            _token: '{{ csrf_token() }}',
                            quotas: payload
                        });

                        // Show success message (using your project's alert system if available, or simple alert)
                        alert('Berhasil menyimpan jatah cuti.');

                    } catch (error) {
                        console.error(error);
                        alert('Gagal menyimpan data. Silakan coba lagi.');
                    } finally {
                        this.saving = false;
                    }
                }
            }
        }
    </script>
</x-layouts.app>