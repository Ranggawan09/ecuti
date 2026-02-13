<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
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
        <div class="col-span-6 sm:col-span-3">
            <x-label for="jabatan" value="{{ __('Jabatan') }}" />
            <x-input id="jabatan" type="text" class="mt-1 block w-full" wire:model.defer="state.jabatan" />
            <x-input-error for="jabatan" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="golongan" value="{{ __('Golongan Ruang') }}" />
            <x-input id="golongan" type="text" class="mt-1 block w-full" wire:model.defer="state.golongan" />
            <x-input-error for="golongan" class="mt-2" />
        </div>

        <!-- Row 3: Unit Kerja dan Masa Kerja -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="unit_kerja" value="{{ __('Unit Kerja') }}" />
            <x-input id="unit_kerja" type="text" class="mt-1 block w-full" wire:model.defer="state.unit_kerja" />
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
            <x-input-error for="email" class="mt-2" />

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
        <div x-data="{ signatureName: null, signaturePreview: null }" class="col-span-6 sm:col-span-4">
            <!-- Signature File Input -->
            <input type="file" class="hidden" wire:model="signature" x-ref="signature" accept=".png,image/png" x-on:change="
                signatureName = $refs.signature.files[0].name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    signaturePreview = e.target.result;
                };
                reader.readAsDataURL($refs.signature.files[0]);
            " />

            <x-label for="signature" value="{{ __('Foto Tanda Tangan') }}" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format PNG, maksimal 1MB</p>

            <!-- Current Signature -->
            <div class="mt-2" x-show="!signaturePreview">
                @if ($this->user->signature_path)
                    <img src="{{ $this->user->signature_url }}" alt="Tanda Tangan" class="h-24 object-contain bg-white border border-gray-200 dark:border-gray-700 rounded p-2">
                @else
                    <div class="h-24 w-48 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                        <span class="text-sm text-gray-400 dark:text-gray-500">Belum ada tanda tangan</span>
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