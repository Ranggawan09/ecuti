<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        {{-- Alert validasi kelengkapan profil --}}
        @if (session('warning') && auth()->user()->employee && !auth()->user()->employee->hasCompleteProfile())
            @php
                $missingFields = auth()->user()->employee->getMissingProfileFields();
            @endphp
            <div class="col-span-6 mb-2">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm">Profil belum lengkap!</p>
                            <p class="text-sm mt-1">Harap lengkapi data berikut sebelum mengajukan cuti:</p>
                            <ul class="list-disc list-inside text-sm mt-1 space-y-0.5">
                                @foreach ($missingFields as $field)
                                    <li>{{ $field }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- General Error Display -->
        @if ($errors->has('general'))
            <div class="col-span-6 sm:col-span-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first('general') }}</span>
                </div>
            </div>
        @endif

        <!-- Foto Profil - Top Left -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden" wire:model="photo" x-ref="photo" x-on:change="
                    photoName = $refs.photo.files[0].name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photoPreview = e.target.result;
                    };
                    reader.readAsDataURL($refs.photo.files[0]);
                " />

                <x-label for="photo" value="{{ __('Foto Profil') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="!photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->nama ?? $this->user->name }}" class="rounded-full h-24 w-24 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-24 h-24 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Pilih Foto Baru') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Hapus Foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Row 1: Nama dan NIP -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="nama" value="{{ __('Nama') }}" />
            <x-input id="nama" type="text" class="mt-1 block w-full" wire:model.defer="state.nama" required autocomplete="name" />
            <x-input-error for="nama" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="nip" value="{{ __('NIP') }}" />
            <x-input id="nip" type="text" class="mt-1 block w-full" wire:model.defer="state.nip" required />
            <x-input-error for="nip" class="mt-2" />
        </div>

        <!-- Row 2: Jabatan dan Golongan Ruang -->
        <div class="col-span-6 sm:col-span-3"
             x-data="{ val: @js(trim($this->state['jabatan'] ?? '')) }"
             x-init="$watch('val', v => val = v)">
            <x-label for="jabatan">
                <span>Jabatan</span>
                <span class="text-red-500 ml-1" title="Wajib diisi">*</span>
            </x-label>
            <input
                id="jabatan"
                type="text"
                wire:model.defer="state.jabatan"
                x-model="val"
                autocomplete="off"
                class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 transition-colors duration-200"
                :class="val && val.trim() !== ''
                    ? 'border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500'
                    : 'border-red-400 bg-red-50 dark:bg-red-900/20 text-gray-900 dark:text-gray-100 focus:ring-red-400 focus:border-red-400 ring-1 ring-red-300'"
            />
            @if(empty(trim($this->state['jabatan'] ?? '')))
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1" id="jabatan-error-msg">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Jabatan wajib diisi
                </p>
            @endif
            <x-input-error for="jabatan" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3"
             x-data="{ val: @js(trim($this->state['golongan'] ?? '')) }"
             x-init="$watch('val', v => val = v)">
            <x-label for="golongan">
                <span>Golongan Ruang</span>
                <span class="text-red-500 ml-1" title="Wajib diisi">*</span>
            </x-label>
            <input
                id="golongan"
                type="text"
                wire:model.defer="state.golongan"
                x-model="val"
                autocomplete="off"
                class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 transition-colors duration-200"
                :class="val && val.trim() !== ''
                    ? 'border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500'
                    : 'border-red-400 bg-red-50 dark:bg-red-900/20 text-gray-900 dark:text-gray-100 focus:ring-red-400 focus:border-red-400 ring-1 ring-red-300'"
            />
            @if(empty(trim($this->state['golongan'] ?? '')))
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Golongan Ruang wajib diisi
                </p>
            @endif
            <x-input-error for="golongan" class="mt-2" />
        </div>

        <!-- Row 3: Unit Kerja dan Masa Kerja -->
        <div class="col-span-6 sm:col-span-3"
             x-data="{ val: @js(trim($this->state['unit_kerja'] ?? '')) }"
             x-init="$watch('val', v => val = v)">
            <x-label for="unit_kerja">
                <span>Unit Kerja</span>
                <span class="text-red-500 ml-1" title="Wajib diisi">*</span>
            </x-label>
            <input
                id="unit_kerja"
                type="text"
                wire:model.defer="state.unit_kerja"
                x-model="val"
                autocomplete="off"
                class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 transition-colors duration-200"
                :class="val && val.trim() !== ''
                    ? 'border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500'
                    : 'border-red-400 bg-red-50 dark:bg-red-900/20 text-gray-900 dark:text-gray-100 focus:ring-red-400 focus:border-red-400 ring-1 ring-red-300'"
            />
            @if(empty(trim($this->state['unit_kerja'] ?? '')))
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Unit Kerja wajib diisi
                </p>
            @endif
            <x-input-error for="unit_kerja" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label value="{{ __('Masa Kerja') }}" />
            <div class="flex gap-2 mt-1">
                <div class="flex-1">
                    <x-input id="masa_kerja_tahun" type="number" min="0" class="block w-full" wire:model.defer="state.masa_kerja_tahun" placeholder="Tahun" />
                </div>
                <div class="flex-1">
                    <x-input id="masa_kerja_bulan" type="number" min="0" max="11" class="block w-full" wire:model.defer="state.masa_kerja_bulan" placeholder="Bulan" />
                </div>
            </div>
            <x-input-error for="masa_kerja_tahun" class="mt-2" />
            <x-input-error for="masa_kerja_bulan" class="mt-2" />
        </div>

        <!-- Row 4: WhatsApp dan Email -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="whatsapp" value="{{ __('WhatsApp') }}" />
            <x-input id="whatsapp" type="text" class="mt-1 block w-full" wire:model.defer="state.whatsapp" placeholder="08xxxxxxxxxx" />
            <x-input-error for="whatsapp" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" autocomplete="username" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-gray-800" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-700">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Row 5: Foto Tanda Tangan -->
        @php $hasSignature = !empty($this->user->signature_path); @endphp
        <div x-data="{ signatureName: null, signaturePreview: null, hasSignature: @js($hasSignature) }" class="col-span-6 sm:col-span-4">
            <!-- Signature File Input -->
            <input type="file" class="hidden" wire:model="signature" x-ref="signature" accept=".png,image/png" x-on:change="
                signatureName = $refs.signature.files[0].name;
                hasSignature = true;
                const reader = new FileReader();
                reader.onload = (e) => {
                    signaturePreview = e.target.result;
                };
                reader.readAsDataURL($refs.signature.files[0]);
            " />

            <x-label for="signature">
                <span>Foto Tanda Tangan</span>
                <span class="text-red-500 ml-1" title="Wajib diisi">*</span>
            </x-label>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format PNG, maksimal 1MB</p>

            <!-- Current Signature / Empty State -->
            <div class="mt-2" x-show="!signaturePreview">
                @if ($this->user->signature_path)
                    <img src="{{ $this->user->signature_url }}" alt="Tanda Tangan" class="h-24 object-contain bg-white border border-gray-200 dark:border-gray-700 rounded p-2">
                @else
                    <div class="h-24 w-48 bg-red-50 dark:bg-red-900/20 border-2 border-dashed border-red-400 rounded flex flex-col items-center justify-center gap-1">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span class="text-xs text-red-500 font-medium">Belum ada tanda tangan</span>
                    </div>
                @endif
            </div>

            <!-- New Signature Preview -->
            <div class="mt-2" x-show="signaturePreview" style="display: none;">
                <img :src="signaturePreview" alt="Preview Tanda Tangan" class="h-24 object-contain bg-white border border-gray-200 dark:border-gray-700 rounded p-2">
            </div>

            <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.signature.click()">
                {{ __('Pilih Foto Tanda Tangan') }}
            </x-secondary-button>

            @if ($this->user->signature_path)
                <x-secondary-button type="button" class="mt-2" wire:click="deleteSignature">
                    {{ __('Hapus Tanda Tangan') }}
                </x-secondary-button>
            @endif

            @if (!$this->user->signature_path)
                <p class="text-red-500 text-xs mt-2 flex items-center gap-1" x-show="!hasSignature">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Foto Tanda Tangan wajib diunggah
                </p>
            @endif

            <x-input-error for="signature" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo,signature">
            {{ __('Save') }}
        </x-button>
    </x-slot>

</x-form-section>