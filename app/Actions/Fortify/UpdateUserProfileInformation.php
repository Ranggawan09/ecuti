<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'golongan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'masa_kerja_tahun' => ['nullable', 'integer', 'min:0'],
            'masa_kerja_bulan' => ['nullable', 'integer', 'min:0', 'max:11'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'nama' => $input['nama'],
                'nip' => $input['nip'],
                'email' => $input['email'],
                'whatsapp' => $input['whatsapp'] ?? null,
            ])->save();
        }

        // Update or create employee data if any employee fields are provided
        if ($user->employee) {
            $user->employee->update([
                'jabatan' => $input['jabatan'] ?? null,
                'golongan' => $input['golongan'] ?? null,
                'unit_kerja' => $input['unit_kerja'] ?? null,
                'masa_kerja_tahun' => $input['masa_kerja_tahun'] ?? null,
                'masa_kerja_bulan' => $input['masa_kerja_bulan'] ?? null,
            ]);
        } elseif (!empty($input['jabatan']) || !empty($input['golongan']) || !empty($input['unit_kerja'])) {
            // Create employee record if it doesn't exist and at least one field is provided
            $user->employee()->create([
                'jabatan' => $input['jabatan'] ?? '',
                'golongan' => $input['golongan'] ?? '',
                'unit_kerja' => $input['unit_kerja'] ?? '',
                'masa_kerja_tahun' => $input['masa_kerja_tahun'] ?? null,
                'masa_kerja_bulan' => $input['masa_kerja_bulan'] ?? null,
            ]);
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'nama' => $input['nama'],
            'nip' => $input['nip'],
            'email' => $input['email'],
            'whatsapp' => $input['whatsapp'] ?? null,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
