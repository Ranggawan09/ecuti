<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;

    protected $listeners = ['refresh' => '$refresh'];

    protected $rules = [
        'state.nama' => 'required|string|max:255',
        'state.nip' => 'required|string|max:255',
        'state.email' => 'required|email|max:255',
        'state.whatsapp' => 'nullable|string|max:20',
        'state.jabatan' => 'nullable|string|max:255',
        'state.golongan' => 'nullable|string|max:50',
        'state.unit_kerja' => 'nullable|string|max:255',
        'state.masa_kerja_tahun' => 'nullable|integer|min:0',
        'state.masa_kerja_bulan' => 'nullable|integer|min:0|max:11',
    ];

    public function mount()
    {
        $user = auth()->user();
        $user->load('employee');
        $employee = $user->employee;

        // Initialize state as empty array first
        $this->state = [];
        
        // Add user data
        $this->state['nama'] = $user->nama ?? '';
        $this->state['nip'] = $user->nip ?? '';
        $this->state['email'] = $user->email ?? '';
        $this->state['whatsapp'] = $user->whatsapp ?? '';
        
        // Add employee data
        if ($employee) {
            $this->state['jabatan'] = $employee->jabatan ?? '';
            $this->state['golongan'] = $employee->golongan ?? '';
            $this->state['unit_kerja'] = $employee->unit_kerja ?? '';
            $this->state['masa_kerja_tahun'] = $employee->masa_kerja_tahun ?? 0;
            $this->state['masa_kerja_bulan'] = $employee->masa_kerja_bulan ?? 0;
        } else {
            $this->state['jabatan'] = '';
            $this->state['golongan'] = '';
            $this->state['unit_kerja'] = '';
            $this->state['masa_kerja_tahun'] = 0;
            $this->state['masa_kerja_bulan'] = 0;
        }
    }

    public function hydrate()
    {
        // Ensure state is initialized on every request
        if (empty($this->state)) {
            $this->mount();
        }
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
            $user->load('employee'); // Tambahkan ini untuk reload employee relationship
            
            // Reload state with fresh data
            $this->mount();

            $this->dispatch('saved');
            $this->dispatch('refresh-navigation-menu');
            
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
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
