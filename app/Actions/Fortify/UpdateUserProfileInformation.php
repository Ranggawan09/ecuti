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
            'tmt_masa_kerja' => ['nullable', 'date'],
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
            $employeeData = $this->buildEmployeeData($input);
            $user->employee()->updateOrCreate(
                ['user_id' => $user->id],
                $employeeData
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
        $employeeData = $this->buildEmployeeData($input);
        $user->employee()->updateOrCreate(
            ['user_id' => $user->id],
            $employeeData
        );

        $user->sendEmailVerificationNotification();
    }

    /**
     * Menyiapkan data employee, menghitung masa_kerja_tahun & bulan
     * secara otomatis dari tmt_masa_kerja apabila tersedia.
     */
    protected function buildEmployeeData(array $input): array
    {
        $tmt = !empty($input['tmt_masa_kerja'])
            ? \Carbon\Carbon::parse($input['tmt_masa_kerja'])
            : null;

        if ($tmt) {
            $diff = $tmt->diff(now());
            $tahun = $diff->y;
            $bulan = $diff->m;
        } else {
            $tahun = $input['masa_kerja_tahun'] ?? 0;
            $bulan = $input['masa_kerja_bulan'] ?? 0;
        }

        return [
            'jabatan'          => $input['jabatan'] ?? null,
            'golongan'         => $input['golongan'] ?? null,
            'unit_kerja'       => $input['unit_kerja'] ?? null,
            'tmt_masa_kerja'   => $tmt ? $tmt->toDateString() : null,
            'masa_kerja_tahun' => $tahun,
            'masa_kerja_bulan' => $bulan,
        ];
    }
}