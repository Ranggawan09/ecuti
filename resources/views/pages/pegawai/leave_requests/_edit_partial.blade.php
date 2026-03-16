@php
    $statusClasses = match($leaveRequest->status) {
        'menunggu_atasan_langsung'       => 'bg-amber-100 dark:bg-amber-500/30 text-amber-600 dark:text-amber-400',
        'menunggu_atasan_tidak_langsung' => 'bg-blue-100 dark:bg-blue-500/30 text-blue-600 dark:text-blue-400',
        'disetujui'                      => 'bg-emerald-100 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
        'perubahan'                      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'ditangguhkan'                   => 'bg-orange-100 dark:bg-orange-500/30 text-orange-600 dark:text-orange-400',
        'tidak_disetujui'                => 'bg-red-100 dark:bg-red-500/30 text-red-600 dark:text-red-400',
        default                          => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
    };
    $statusText = match($leaveRequest->status) {
        'menunggu_atasan_langsung'       => 'Menunggu Atasan Langsung',
        'menunggu_atasan_tidak_langsung' => 'Menunggu Atasan Tidak Langsung',
        'disetujui'                      => 'Disetujui',
        'perubahan'                      => 'Perubahan',
        'ditangguhkan'                   => 'Ditangguhkan',
        'tidak_disetujui'                => 'Tidak Disetujui',
        default                          => ucfirst($leaveRequest->status),
    };
@endphp

<form id="editLeaveForm" class="space-y-4">
    @csrf

    <!-- Informasi Pegawai (readonly) -->
    <div class="bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-500/30 rounded-lg p-4">
        <h4 class="text-xs font-semibold text-violet-700 dark:text-violet-400 uppercase tracking-wider mb-3">Informasi Pegawai</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Nama</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ auth()->user()->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">NIP</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ auth()->user()->nip ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jabatan</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $employee->jabatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Unit Kerja</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $employee->unit_kerja ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Jenis Cuti -->
    <div>
        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" for="edit_leave_type_id">
            Jenis Cuti <span class="text-red-500">*</span>
        </label>
        <select id="edit_leave_type_id" name="leave_type_id" class="form-select w-full" required>
            <option value="">Pilih Jenis Cuti</option>
            @foreach($leaveTypes as $type)
                <option value="{{ $type->id }}" {{ $leaveRequest->leave_type_id == $type->id ? 'selected' : '' }}>
                    {{ $type->name }} (Max: {{ $type->max_days }} hari)
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" for="edit_start_date">
                Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input
                id="edit_start_date"
                name="start_date"
                type="date"
                class="form-input w-full"
                value="{{ $leaveRequest->start_date->format('Y-m-d') }}"
                required
            >
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" for="edit_end_date">
                Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input
                id="edit_end_date"
                name="end_date"
                type="date"
                class="form-input w-full"
                value="{{ $leaveRequest->end_date->format('Y-m-d') }}"
                required
            >
        </div>
    </div>

    <!-- Total Hari -->
    <div>
        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1">Total Hari</label>
        <div class="inline-flex font-medium rounded-full text-center px-4 py-2 bg-violet-100 dark:bg-violet-500/30 text-violet-600 dark:text-violet-400">
            <span id="edit_total_days_display">{{ $leaveRequest->total_days }} hari</span>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dihitung otomatis berdasarkan tanggal</p>
    </div>

    <!-- Alasan -->
    <div>
        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" for="edit_reason">
            Alasan Cuti <span class="text-red-500">*</span>
        </label>
        <textarea
            id="edit_reason"
            name="reason"
            rows="3"
            class="form-textarea w-full"
            placeholder="Jelaskan alasan pengajuan cuti..."
            required
        >{{ $leaveRequest->reason }}</textarea>
    </div>

    <!-- Alamat Selama Cuti -->
    <div>
        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" for="edit_address">
            Alamat Selama Cuti <span class="text-red-500">*</span>
        </label>
        <input
            id="edit_address"
            name="address_during_leave"
            type="text"
            class="form-input w-full"
            value="{{ $leaveRequest->address_during_leave }}"
            placeholder="Alamat yang bisa dihubungi selama cuti"
            required
        >
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-medium text-gray-800 dark:text-gray-100 mb-1">Status Saat Ini</label>
        <span class="inline-flex font-medium rounded-full text-center px-3 py-1 text-sm {{ $statusClasses }}">{{ $statusText }}</span>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status tidak dapat diubah oleh pegawai</p>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-700/60">
        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                class="flex-1 btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
            Batal
        </button>
        <button type="submit"
                class="flex-1 btn bg-violet-500 hover:bg-violet-600 text-white">
            <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                <path d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
            </svg>
            Update
        </button>
    </div>
</form>
