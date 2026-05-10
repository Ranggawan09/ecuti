<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="publicHolidaysPage()">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tanggal Merah</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola tanggal merah per tahun dan per bulan</p>
        </div>

        {{-- Tambah Tahun Baru --}}
        <div class="flex items-center gap-2">
            <input
                type="number"
                x-model.number="newYear"
                min="2000" max="2100"
                class="form-input w-28 text-sm"
                placeholder="Tahun"
            >
            <button
                @click="addYear()"
                class="btn bg-violet-500 hover:bg-violet-600 text-white"
            >
                <svg class="fill-current shrink-0 mr-1.5" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1Z"/>
                </svg>
                Tambah Tahun
            </button>
        </div>
    </div>

    {{-- ===== TABEL DAFTAR TAHUN ===== --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                Daftar Tahun
                <span class="text-gray-400 dark:text-gray-500 font-medium" x-text="'(' + years.length + ')'"></span>
            </h2>
        </header>

        <div class="overflow-x-auto">
            <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700/60">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700/60">
                    <tr>
                        <th class="px-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Tahun</div>
                        </th>
                        <th class="px-5 py-3 whitespace-nowrap">
                            <div class="font-semibold text-left">Status</div>
                        </th>
                        <th class="px-5 py-3 whitespace-nowrap text-right">
                            <div class="font-semibold">Aksi</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                    <template x-for="yr in years" :key="yr">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                         :class="yr === {{ $currentYear }} ? 'bg-violet-100 dark:bg-violet-500/30' : 'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="fill-current w-4 h-4"
                                             :class="yr === {{ $currentYear }} ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500'"
                                             viewBox="0 0 16 16">
                                            <path d="M15 2h-2V0h-2v2H5V0H3v2H1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm-1 12H2V6h12v8Zm0-10H2V4h12v1Z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-800 dark:text-gray-100" x-text="yr"></span>
                                    <span x-show="yr === {{ $currentYear }}"
                                          class="text-xs bg-violet-100 dark:bg-violet-500/30 text-violet-600 dark:text-violet-400 px-2 py-0.5 rounded-full font-medium">
                                        Tahun ini
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Klik untuk melihat & mengedit per bulan</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <button
                                    @click="openYearModal(yr)"
                                    class="btn-sm bg-violet-500 hover:bg-violet-600 text-white gap-1.5"
                                >
                                    <svg class="fill-current shrink-0 w-3.5 h-3.5" viewBox="0 0 16 16">
                                        <path d="M15 2h-2V0h-2v2H5V0H3v2H1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm-1 12H2V6h12v8Zm0-10H2V4h12v1Z"/>
                                    </svg>
                                    Edit Per Bulan
                                </button>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty state --}}
                    <tr x-show="years.length === 0">
                        <td colspan="3" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">
                            <svg class="inline-block w-12 h-12 mb-3 opacity-30 fill-current" viewBox="0 0 16 16">
                                <path d="M15 2h-2V0h-2v2H5V0H3v2H1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm-1 12H2V6h12v8Zm0-10H2V4h12v1Z"/>
                            </svg>
                            <p class="font-medium">Belum ada data tahun</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== POPUP MODAL EDIT PER BULAN ===== --}}
    <div
        x-show="showModal"
        class="fixed inset-0 z-[1000] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    >
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/60 dark:bg-gray-900/80 backdrop-blur-sm" @click="closeModal()"></div>

        {{-- Panel --}}
        <div
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl mx-auto overflow-hidden flex flex-col"
            style="max-height: 92vh;"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="closeModal()"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700/60 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-500/30 flex items-center justify-center">
                        <svg class="fill-current w-4 h-4 text-red-500" viewBox="0 0 16 16">
                            <path d="M15 2h-2V0h-2v2H5V0H3v2H1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm-1 12H2V6h12v8Zm0-10H2V4h12v1Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                             Tanggal Merah
                            <span class="text-violet-500" x-text="selectedYear"></span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pisahkan tanggal dengan tanda koma (contoh: 1, 5, 17)</p>
                    </div>
                </div>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 16 16">
                        <path d="M12.7 3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 5.2 4.7 1.9c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4L6.6 6.6 3.3 9.9c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3l3.3-3.3 3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L9.4 6.6l3.3-3.3Z"/>
                    </svg>
                </button>
            </div>

            {{-- Loading state --}}
            <div x-show="modalLoading" class="flex-1 flex items-center justify-center py-20">
                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                    <svg class="animate-spin w-8 h-8" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span class="text-sm">Memuat data...</span>
                </div>
            </div>

            {{-- Tabel 12 Bulan --}}
            <div x-show="!modalLoading" class="overflow-y-auto flex-1">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-gray-50 dark:bg-gray-900/60 border-b border-gray-200 dark:border-gray-700/60">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 w-28">Bulan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-red-400 shrink-0"></span>
                                    Tanggal Merah
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        <template x-for="(row, idx) in modalRows" :key="row.bulan">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                                {{-- Nama Bulan --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold w-5 text-center text-gray-400 dark:text-gray-500" x-text="row.bulan"></span>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200" x-text="namaBulan(row.bulan)"></span>
                                    </div>
                                </td>
                                {{-- Tanggal Merah Input --}}
                                <td class="px-4 py-2">
                                    <div class="relative">
                                        <input
                                            type="text"
                                            x-model="row.tanggal_merah"
                                            :id="'tr-' + idx"
                                            placeholder="1, 17, 25"
                                            class="form-input w-full text-sm pr-8 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-red-400 focus:ring-red-300"
                                            @blur="formatInput($event)"
                                        >
                                        <span x-show="row.tanggal_merah"
                                              class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-red-400 font-medium select-none"
                                              x-text="countDates(row.tanggal_merah) + ' tgl'">
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700/60 shrink-0 bg-gray-50 dark:bg-gray-900/20">
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    Perubahan belum tersimpan akan hilang jika modal ditutup
                </div>
                <div class="flex gap-3">
                    <button @click="closeModal()" class="btn bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 text-gray-600 dark:text-gray-300 text-sm">
                        Batal
                    </button>
                    <button
                        @click="saveAll()"
                        :disabled="saving"
                        class="btn bg-violet-500 hover:bg-violet-600 text-white text-sm relative"
                        :class="saving ? 'opacity-75 cursor-not-allowed' : ''"
                    >
                        <svg x-show="saving" class="animate-spin w-4 h-4 mr-1.5 fill-none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="saving ? 'Menyimpan...' : 'Simpan Semua'"></span>
                    </button>
                </div>
            </div>

            {{-- Toast notif (inside modal) --}}
            <div
                x-show="toast.show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute bottom-20 left-1/2 -translate-x-1/2 z-50 px-5 py-2.5 rounded-xl shadow-lg text-sm font-medium text-white"
                :class="toast.success ? 'bg-emerald-500' : 'bg-red-500'"
                x-text="toast.message"
                x-cloak
            ></div>
        </div>
    </div>

</div>

<script>
function publicHolidaysPage() {
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    return {
        years: @json($existingYears),
        newYear: {{ $currentYear + 1 }},

        // Modal state
        showModal: false,
        selectedYear: null,
        modalLoading: false,
        modalRows: [],
        saving: false,
        toast: { show: false, success: true, message: '' },

        namaBulan(n) {
            return MONTHS[n - 1] ?? '';
        },

        countDates(str) {
            if (!str || !str.trim()) return 0;
            return str.split(',').filter(s => s.trim() !== '').length;
        },

        formatInput(e) {
            const raw = e.target.value;
            if (!raw.trim()) return;
            const parts = raw.split(/[\s,]+/).map(s => parseInt(s)).filter(n => !isNaN(n) && n >= 1 && n <= 31);
            const unique = [...new Set(parts)].sort((a, b) => a - b);
            e.target.value = unique.join(', ');
            // Sync back to Alpine model via input event
            e.target.dispatchEvent(new Event('input'));
        },

        addYear() {
            const yr = parseInt(this.newYear);
            if (!yr || yr < 2000 || yr > 2100) {
                alert('Masukkan tahun yang valid (2000–2100)');
                return;
            }
            if (!this.years.includes(yr)) {
                this.years.unshift(yr);
                this.years.sort((a, b) => b - a);
            }
            this.openYearModal(yr);
        },

        async openYearModal(yr) {
            this.selectedYear = yr;
            this.showModal = true;
            this.modalLoading = true;
            this.modalRows = [];

            try {
                const resp = await axios.get(`/admin/public-holidays/${yr}/data`);
                this.modalRows = resp.data;
            } catch (e) {
                // Fallback: buat 12 baris kosong
                this.modalRows = Array.from({ length: 12 }, (_, i) => ({
                    bulan: i + 1,
                    tanggal_merah: '',
                }));
            } finally {
                this.modalLoading = false;
            }
        },

        closeModal() {
            this.showModal = false;
            this.selectedYear = null;
            this.modalRows = [];
        },

        async saveAll() {
            this.saving = true;
            try {
                await axios.post('/admin/public-holidays/bulk-save', {
                    _token: '{{ csrf_token() }}',
                    tahun: this.selectedYear,
                    rows: this.modalRows,
                });
                this.showToast(true, 'Data berhasil disimpan!');
                if (!this.years.includes(this.selectedYear)) {
                    this.years.unshift(this.selectedYear);
                    this.years.sort((a, b) => b - a);
                }
            } catch (e) {
                const msg = e.response?.data?.message ?? 'Terjadi kesalahan, coba lagi.';
                this.showToast(false, msg);
            } finally {
                this.saving = false;
            }
        },

        showToast(success, message) {
            this.toast = { show: true, success, message };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },
    };
}
</script>
</x-layouts.app>
