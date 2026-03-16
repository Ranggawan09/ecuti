<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'masa_kerja_tahun' => ['nullable', 'integer', 'min:0'],
            'masa_kerja_bulan' => ['nullable', 'integer', 'min:0', 'max:11'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'signature' => ['nullable', 'image', 'mimes:png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (isset($input['signature'])) {
            $user->updateSignature($input['signature']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            // Update user data
            $user->forceFill([
                'nama' => $input['nama'],
                'nip' => $input['nip'],
                'email' => $input['email'],
                'whatsapp' => $input['whatsapp'] ?? null,
            ])->save();

            // Update or create employee data
            $user->employee()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'jabatan' => $input['jabatan'] ?? null,
                    'golongan' => $input['golongan'] ?? null,
                    'unit_kerja' => $input['unit_kerja'] ?? null,
                    'masa_kerja_tahun' => $input['masa_kerja_tahun'] ?? 0,
                    'masa_kerja_bulan' => $input['masa_kerja_bulan'] ?? 0,
                ]
            );
        }
    }

    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'nama' => $input['nama'],
            'nip' => $input['nip'],
            'email' => $input['email'],
            'whatsapp' => $input['whatsapp'] ?? null,
            'email_verified_at' => null,
        ])->save();

        // Update or create employee data
        $user->employee()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'jabatan' => $input['jabatan'] ?? null,
                'golongan' => $input['golongan'] ?? null,
                'unit_kerja' => $input['unit_kerja'] ?? null,
                'masa_kerja_tahun' => $input['masa_kerja_tahun'] ?? 0,
                'masa_kerja_bulan' => $input['masa_kerja_bulan'] ?? 0,
            ]
        );

        $user->sendEmailVerificationNotification();
    }
}