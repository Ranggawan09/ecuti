<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $user = auth()->user();
        $employee = $user->employee;

        $this->state = [
            'nama' => $user->nama ?? '',
            'nip' => $user->nip ?? '',
            'email' => $user->email ?? '',
            'whatsapp' => $user->whatsapp ?? '',
            'jabatan' => $employee?->jabatan ?? '',
            'golongan' => $employee?->golongan ?? '',
            'unit_kerja' => $employee?->unit_kerja ?? '',
            'masa_kerja_tahun' => $employee?->masa_kerja_tahun ?? '',
            'masa_kerja_bulan' => $employee?->masa_kerja_bulan ?? '',
        ];
    }

    public function updateProfileInformation()
    {
        $this->resetErrorBag();

        try {
            $user = auth()->user();

            app(\Laravel\Fortify\Contracts\UpdatesUserProfileInformation::class)->update(
                $user,
                $this->photo
                    ? array_merge($this->state, ['photo' => $this->photo])
                    : $this->state
            );

            if (isset($this->photo)) {
                return redirect()->route('profile.show');
            }

            // Reload fresh data from database
            $user->refresh();
            if ($user->employee) {
                $user->employee->refresh();
            }
            
            // Reload state with fresh data
            $this->mount();

            $this->dispatch('saved');
            $this->dispatch('refresh-navigation-menu');
            
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            $this->addError('general', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function deleteProfilePhoto()
    {
        auth()->user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }

    public function getUserProperty()
    {
        return auth()->user();
    }

    public function getVerificationLinkSentProperty()
    {
        return session('status') === 'verification-link-sent';
    }

    public function sendEmailVerification()
    {
        auth()->user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }
}
