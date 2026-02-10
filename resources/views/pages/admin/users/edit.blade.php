<x-layouts.app>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="btn-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300 mb-4">
            <svg class="fill-current mr-2" width="16" height="16" viewBox="0 0 16 16">
                <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)" />
            </svg>
            <span>Kembali ke Daftar User</span>
        </a>
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Edit User ✨</h1>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <div class="p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="nama">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="nama"
                            name="nama"
                            class="form-input w-full @error('nama') border-red-300 @enderror"
                            type="text"
                            value="{{ old('nama', $user->nama) }}"
                            required
                        >
                        @error('nama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="nip">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="nip"
                            name="nip"
                            class="form-input w-full @error('nip') border-red-300 @enderror"
                            type="text"
                            value="{{ old('nip', $user->nip) }}"
                            required
                        >
                        @error('nip')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            name="email"
                            class="form-input w-full @error('email') border-red-300 @enderror"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="whatsapp">
                            No WhatsApp
                        </label>
                        <input
                            id="whatsapp"
                            name="whatsapp"
                            class="form-input w-full @error('whatsapp') border-red-300 @enderror"
                            type="text"
                            value="{{ old('whatsapp', $user->whatsapp) }}"
                            placeholder="08xxxxxxxxxx"
                        >
                        @error('whatsapp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="role">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            class="form-select w-full @error('role') border-red-300 @enderror"
                            required
                        >
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kepegawaian" {{ old('role', $user->role) == 'kepegawaian' ? 'selected' : '' }}>Kepegawaian</option>
                            <option value="atasan_langsung" {{ old('role', $user->role) == 'atasan_langsung' ? 'selected' : '' }}>Atasan Langsung</option>
                            <option value="atasan_tidak_langsung" {{ old('role', $user->role) == 'atasan_tidak_langsung' ? 'selected' : '' }}>Atasan Tidak Langsung</option>
                            <option value="pegawai" {{ old('role', $user->role) == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supervisor Fields (Only for Pegawai) -->
                    <div id="supervisorFields" class="space-y-6" style="display: none;">
                        <!-- Atasan Langsung -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="atasan_langsung_id">
                                Atasan Langsung <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="atasan_langsung_id"
                                name="atasan_langsung_id"
                                class="form-select w-full @error('atasan_langsung_id') border-red-300 @enderror"
                            >
                                <option value="">Pilih Atasan Langsung</option>
                                @foreach(\App\Models\User::whereIn('role', ['atasan_langsung', 'atasan_tidak_langsung'])->orderBy('nama')->get() as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('atasan_langsung_id', $user->employee->atasan_langsung_id ?? '') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->nama }} ({{ $supervisor->nip }})
                                    </option>
                                @endforeach
                            </select>
                            @error('atasan_langsung_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Atasan Tidak Langsung -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="atasan_tidak_langsung_id">
                                Atasan Tidak Langsung <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="atasan_tidak_langsung_id"
                                name="atasan_tidak_langsung_id"
                                class="form-select w-full @error('atasan_tidak_langsung_id') border-red-300 @enderror"
                            >
                                <option value="">Pilih Atasan Tidak Langsung</option>
                                @foreach(\App\Models\User::whereIn('role', ['atasan_langsung', 'atasan_tidak_langsung'])->orderBy('nama')->get() as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('atasan_tidak_langsung_id', $user->employee->atasan_tidak_langsung_id ?? '') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->nama }} ({{ $supervisor->nip }})
                                    </option>
                                @endforeach
                            </select>
                            @error('atasan_tidak_langsung_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700/60 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Ubah Password (Opsional)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password">
                            Password Baru
                        </label>
                        <input
                            id="password"
                            name="password"
                            class="form-input w-full @error('password') border-red-300 @enderror"
                            type="password"
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Minimal 8 karakter</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input w-full"
                            type="password"
                        >
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const supervisorFields = document.getElementById('supervisorFields');
        const atasanLangsungSelect = document.getElementById('atasan_langsung_id');
        const atasanTidakLangsungSelect = document.getElementById('atasan_tidak_langsung_id');

        function toggleSupervisorFields() {
            if (roleSelect.value === 'pegawai') {
                supervisorFields.style.display = 'block';
                atasanLangsungSelect.setAttribute('required', 'required');
                atasanTidakLangsungSelect.setAttribute('required', 'required');
            } else {
                supervisorFields.style.display = 'none';
                atasanLangsungSelect.removeAttribute('required');
                atasanTidakLangsungSelect.removeAttribute('required');
                atasanLangsungSelect.value = '';
                atasanTidakLangsungSelect.value = '';
            }
        }

        // Initialize on page load
        toggleSupervisorFields();

        // Listen for changes
        roleSelect.addEventListener('change', toggleSupervisorFields);
    });
</script>

</x-layouts.app>