<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('kepegawaian.leave-requests.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M6.6 13.4L1.2 8l5.4-5.4 1.4 1.4L4.4 7.6h10.4v2H4.4l3.6 3.6-1.4 1.4z" />
                </svg>
                <span>Kembali</span>
            </a>
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tambah Cuti Pegawai</h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Form Data Cuti</h2>
        </div>
        <form action="{{ route('kepegawaian.leave-requests.store') }}" method="POST" x-data="leaveRequestForm()">
            @csrf
            <div class="p-6 space-y-6">

                <!-- Employee Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="employee_id">
                        Pegawai <span class="text-red-500">*</span>
                    </label>
                    <select id="employee_id" name="employee_id" class="form-select w-full @error('employee_id') border-red-300 @enderror" required>
                        <option value="">Pilih Pegawai</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->nama ?? '-' }} ({{ $employee->user->nip ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Leave Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="leave_type_id">
                        Jenis Cuti <span class="text-red-500">*</span>
                    </label>
                    <select id="leave_type_id" name="leave_type_id" class="form-select w-full @error('leave_type_id') border-red-300 @enderror" required>
                        <option value="">Pilih Jenis Cuti</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} (Max: {{ $type->max_days }} hari)
                            </option>
                        @endforeach
                    </select>
                    @error('leave_type_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="start_date">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="start_date" 
                            name="start_date" 
                            type="date" 
                            class="form-input w-full @error('start_date') border-red-300 @enderror" 
                            value="{{ old('start_date') }}"
                            x-model="startDate"
                            @change="calculateDays()"
                            required
                        >
                        @error('start_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="end_date">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="end_date" 
                            name="end_date" 
                            type="date" 
                            class="form-input w-full @error('end_date') border-red-300 @enderror" 
                            value="{{ old('end_date') }}"
                            x-model="endDate"
                            @change="calculateDays()"
                            required
                        >
                        @error('end_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Total Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="total_days">
                        Total Hari <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="total_days" 
                        name="total_days" 
                        type="number" 
                        class="form-input w-full @error('total_days') border-red-300 @enderror" 
                        value="{{ old('total_days') }}"
                        x-model="totalDays"
                        min="1"
                        required
                        readonly
                    >
                    @error('total_days')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total hari akan dihitung otomatis berdasarkan tanggal mulai dan selesai</p>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="reason">
                        Alasan Cuti <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="reason" 
                        name="reason" 
                        rows="4" 
                        class="form-textarea w-full @error('reason') border-red-300 @enderror" 
                        placeholder="Jelaskan alasan pengajuan cuti..."
                        required
                    >{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address During Leave -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="address_during_leave">
                        Alamat Selama Cuti <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="address_during_leave" 
                        name="address_during_leave" 
                        type="text" 
                        class="form-input w-full @error('address_during_leave') border-red-300 @enderror" 
                        value="{{ old('address_during_leave') }}"
                        placeholder="Masukkan alamat yang bisa dihubungi selama cuti"
                        required
                    >
                    @error('address_during_leave')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="form-select w-full @error('status') border-red-300 @enderror" required>
                        <option value="menunggu_atasan_langsung" {{ old('status') == 'menunggu_atasan_langsung' ? 'selected' : '' }}>Menunggu Atasan Langsung</option>
                        <option value="menunggu_atasan_tidak_langsung" {{ old('status') == 'menunggu_atasan_tidak_langsung' ? 'selected' : '' }}>Menunggu Atasan Tidak Langsung</option>
                        <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="perubahan" {{ old('status') == 'perubahan' ? 'selected' : '' }}>Perubahan</option>
                        <option value="ditangguhkan" {{ old('status') == 'ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                        <option value="tidak_disetujui" {{ old('status') == 'tidak_disetujui' ? 'selected' : '' }}>Tidak Disetujui</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/60">
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('kepegawaian.leave-requests.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                        <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
                        </svg>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
function leaveRequestForm() {
    return {
        startDate: '{{ old('start_date') }}',
        endDate: '{{ old('end_date') }}',
        totalDays: {{ old('total_days', 0) }},
        
        calculateDays() {
            if (this.startDate && this.endDate) {
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                
                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    this.totalDays = diffDays;
                } else {
                    this.totalDays = 0;
                }
            }
        }
    }
}
</script>
</x-layouts.app>
