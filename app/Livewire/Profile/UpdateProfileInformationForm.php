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
    public $signature;

    protected $listeners = ['refresh' => '$refresh'];

    // Validation is handled by the Fortify action UpdateUserProfileInformation

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
            $this->state['tmt_masa_kerja'] = $employee->tmt_masa_kerja
                ? $employee->tmt_masa_kerja->format('Y-m-d')
                : null;
            // Legacy fields tetap diisi sebagai fallback
            $this->state['masa_kerja_tahun'] = $employee->masa_kerja->tahun ?? 0;
            $this->state['masa_kerja_bulan'] = $employee->masa_kerja->bulan ?? 0;
        } else {
            $this->state['jabatan'] = '';
            $this->state['golongan'] = '';
            $this->state['unit_kerja'] = '';
            $this->state['tmt_masa_kerja'] = null;
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

            $input = $this->state;
            
            if ($this->photo) {
                $input['photo'] = $this->photo;
            }
            
            if ($this->signature) {
                $input['signature'] = $this->signature;
            }

            app(\Laravel\Fortify\Contracts\UpdatesUserProfileInformation::class)->update(
                $user,
                $input
            );

            $hasFiles = isset($this->photo) || isset($this->signature);
            
            // Clear temporary file properties
            $this->photo = null;
            $this->signature = null;

            if ($hasFiles) {
                return redirect()->route('profile.show');
            }

            // Reload fresh data from database
            $user->refresh();
            $user->load('employee');
            
            // Reload state with fresh data
            $this->mount();

            $this->dispatch('saved');
            $this->dispatch('refresh-navigation-menu');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Debug information
            $debugMsg = '';
            if ($this->photo) {
                $path = $this->photo->getRealPath();
                $exists = $path ? (file_exists($path) ? 'Yes' : 'No') : 'N/A';
                $debugMsg .= " Photo[Path:$path, Exists:$exists]";
            }
            if ($this->signature) {
                $path = $this->signature->getRealPath();
                $exists = $path ? (file_exists($path) ? 'Yes' : 'No') : 'N/A';
                $debugMsg .= " Sig[Path:$path, Exists:$exists]";
            }

            if (str_contains($e->getMessage(), 'file_size') || str_contains($e->getMessage(), 'is not readable')) {
                $this->addError('general', 'Error sistem file: ' . $e->getMessage() . $debugMsg);
                $this->photo = null;
                $this->signature = null;
            } else {
                $this->addError('general', 'Terjadi kesalahan: ' . $e->getMessage() . $debugMsg);
            }
        }
    }

    public function deleteProfilePhoto()
    {
        auth()->user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    public function deleteSignature()
    {
        auth()->user()->deleteSignature();

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
