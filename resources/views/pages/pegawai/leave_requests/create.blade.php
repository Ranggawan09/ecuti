<x-layouts.app>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('pegawai.leave-requests.index') }}"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                    <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L1.2 8l5.4-5.4 1.4 1.4L4.4 7.6h10.4v2H4.4l3.6 3.6-1.4 1.4z" />
                    </svg>
                    <span>Kembali</span>
                </a>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Ajukan Cuti</h1>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Form Pengajuan Cuti</h2>
            </div>
            <form action="{{ route('pegawai.leave-requests.store') }}" method="POST"
                x-data="leaveRequestForm(@js($leaveTypes), @js($publicHolidays))">
                @csrf
                <div class="p-6 space-y-6">

                    <!-- Employee Information (Display Only) -->
                    <div
                        class="bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-500/30 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-violet-800 dark:text-violet-300 uppercase mb-3">Informasi
                            Pegawai</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                    Pegawai</label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ auth()->user()->nama ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIP</label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ auth()->user()->nip ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan</label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $employee->jabatan ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit
                                    Kerja</label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $employee->unit_kerja ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2"
                            for="leave_type_id">
                            Jenis Cuti <span class="text-red-500">*</span>
                        </label>
                        <select id="leave_type_id" name="leave_type_id"
                            class="form-select w-full @error('leave_type_id') border-red-300 @enderror"
                            x-model="leaveTypeId" @change="checkLeaveType()" required>
                            <option value="">Pilih Jenis Cuti</option>
                            <template x-for="type in leaveTypes" :key="type.id">
                                <option :value="type.id" x-text="`${type.name} (Max: ${type.max_days} hari)`"
                                    :selected="type.id == '{{ old('leave_type_id') }}'"></option>
                            </template>
                        </select>
                        @error('leave_type_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Penangguhan Checkbox (Only for Cuti Tahunan) -->
                        <div x-show="isCutiTahunan"
                            class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700/50 rounded-lg">
                            <label class="flex items-start text-sm cursor-pointer">
                                <input type="checkbox" name="is_penangguhan" x-model="isPenangguhan"
                                    @change="calculateDays()" value="1"
                                    class="form-checkbox text-yellow-500 rounded border-gray-300 dark:border-gray-600 mt-0.5"
                                    {{ old('is_penangguhan') ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">
                                    <strong>Ajukan Penangguhan Sisa Cuti</strong><br>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">Centang opsi ini HANYA jika
                                        Anda ingin menangguhkan (menyimpan) sisa cuti tahun ini hingga maksimal 6 hari
                                        untuk dipakai di tahun depan. Pengajuan Anda tidak akan dianggap sebagai cuti
                                        libur.</span>
                                </span>
                            </label>
                            <p x-show="isPenangguhan && totalDays > 6" class="text-sm text-red-500 mt-2 font-medium">
                                Maksimal penangguhan cuti adalah 6 hari.</p>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2">
                            Rentang Tanggal Cuti <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            class="form-input w-full @error('start_date') border-red-300 @enderror @error('end_date') border-red-300 @enderror"
                            x-ref="dateRange" placeholder="Pilih rentang tanggal cuti (Mulai - Selesai)" required>
                        <input type="hidden" name="start_date" x-model="startDate">
                        <input type="hidden" name="end_date" x-model="endDate">
                        @error('start_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        @error('end_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Hari (Display Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2">
                            Total Hari
                        </label>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div
                                class="inline-flex font-medium rounded-full text-center px-4 py-2 bg-violet-100 dark:bg-violet-500/30 text-violet-600 dark:text-violet-400">
                                <span x-text="totalDays > 0 ? totalDays + ' hari kerja' : '-'"></span>
                            </div>
                            <!-- Warning Badge for Exceeding Limit -->
                            <div x-show="isExceedingLimit" x-cloak
                                class="inline-flex items-center font-medium rounded-full text-center px-3 py-1 bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400 animate-pulse border border-red-200 dark:border-red-700/50">
                                <svg class="w-4 h-4 mr-1.5 shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Melebihi jatah sisa cuti!</span>
                            </div>
                            <span x-show="workingDaysNote" x-text="workingDaysNote"
                                class="text-xs text-amber-600 dark:text-amber-400 font-medium bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 rounded-full border border-amber-200 dark:border-amber-700/50">
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sabtu, Minggu, dan tanggal merah tidak
                            dihitung</p>
                        @error('total_days')
                            <p class="text-sm text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="reason">
                            Alasan Cuti <span class="text-red-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="4"
                            class="form-textarea w-full @error('reason') border-red-300 @enderror"
                            placeholder="Jelaskan alasan pengajuan cuti..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address During Leave -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2"
                            for="address_during_leave">
                            Alamat Selama Cuti <span class="text-red-500">*</span>
                        </label>
                        <input id="address_during_leave" name="address_during_leave" type="text"
                            class="form-input w-full @error('address_during_leave') border-red-300 @enderror"
                            value="{{ old('address_during_leave') }}"
                            placeholder="Masukkan alamat yang bisa dihubungi selama cuti" required>
                        @error('address_during_leave')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/60">
                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('pegawai.leave-requests.index') }}"
                            class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="btn bg-violet-500 hover:bg-violet-600 text-white disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed"
                            :disabled="isExceedingLimit || (isCutiTahunan && isPenangguhan && totalDays > 6)">
                            <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                                <path
                                    d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
                            </svg>
                            <span x-text="isCutiTahunan && isPenangguhan ? 'Ajukan Penangguhan' : 'Ajukan Cuti'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        function leaveRequestForm(leaveTypesData, publicHolidays) {
            return {
                leaveTypes: leaveTypesData,
                leaveTypeId: '{{ old('leave_type_id') }}',
                isCutiTahunan: false,
                isPenangguhan: {{ old('is_penangguhan', 'false') }},
                startDate: '{{ old('start_date') }}',
                endDate: '{{ old('end_date') }}',
                totalDays: 0,
                calendarDays: 0,
                workingDaysNote: '',
                publicHolidays: publicHolidays,

                get isExceedingLimit() {
                    if (!this.leaveTypeId || this.totalDays <= 0) return false;
                    const selectedType = this.leaveTypes.find(t => t.id == this.leaveTypeId);
                    if (!selectedType) return false;
                    return this.totalDays > selectedType.max_days;
                },

                init() {
                    if (this.leaveTypeId) {
                        this.checkLeaveType();
                    }
                    this.calculateDays();

                    const fp = flatpickr(this.$refs.dateRange, {
                        mode: 'range',
                        minDate: new Date(new Date().setDate(new Date().getDate() + 1)),
                        dateFormat: 'Y-m-d',
                        defaultDate: (this.startDate && this.endDate) ? [this.startDate, this.endDate] : null,
                        onChange: (selectedDates, dateStr, instance) => {
                            if (selectedDates.length === 2) {
                                this.startDate = instance.formatDate(selectedDates[0], 'Y-m-d');
                                this.endDate = instance.formatDate(selectedDates[1], 'Y-m-d');
                                this.calculateDays();
                            } else if (selectedDates.length === 1) {
                                this.startDate = instance.formatDate(selectedDates[0], 'Y-m-d');
                                this.endDate = this.startDate;
                                this.calculateDays();
                            } else {
                                this.startDate = '';
                                this.endDate = '';
                                this.totalDays = 0;
                            }
                        }
                    });
                },

                checkLeaveType() {
                    const selectedType = this.leaveTypes.find(t => t.id == this.leaveTypeId);
                    if (selectedType && selectedType.name.toLowerCase() === 'cuti tahunan') {
                        this.isCutiTahunan = true;
                    } else {
                        this.isCutiTahunan = false;
                        this.isPenangguhan = false;
                    }
                },

                calculateDays() {
                    if (this.startDate && this.endDate) {
                        const start = new Date(this.startDate);
                        const end = new Date(this.endDate);

                        if (end >= start) {
                            let workingDays = 0;
                            let skippedWeekend = 0;
                            let skippedHoliday = 0;
                            let calendarDays = 0;

                            const cur = new Date(start);
                            while (cur <= end) {
                                calendarDays++;
                                const dow = cur.getDay(); // 0=Sun, 6=Sat
                                const ymd = cur.toISOString().slice(0, 10);

                                if (dow === 0 || dow === 6) {
                                    skippedWeekend++;
                                } else if (this.publicHolidays.includes(ymd)) {
                                    skippedHoliday++;
                                } else {
                                    workingDays++;
                                }

                                cur.setDate(cur.getDate() + 1);
                            }

                            this.totalDays = workingDays;
                            this.calendarDays = calendarDays;

                            // Susun keterangan
                            const parts = [];
                            if (skippedWeekend > 0) parts.push(skippedWeekend + ' weekend');
                            if (skippedHoliday > 0) parts.push(skippedHoliday + ' libur nasional');
                            this.workingDaysNote = parts.length > 0
                                ? '(' + calendarDays + ' hari kalender, ' + parts.join(' + ') + ' tidak dihitung)'
                                : '';
                        } else {
                            this.totalDays = 0;
                            this.workingDaysNote = '';
                        }
                    }
                }
            }
        }
    </script>
</x-layouts.app>