<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'whatsapp',
        'password',
        'role',
        'roles',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    /* ================== RELATIONS ================== */

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /* ================== HELPERS ================== */

    public function isRole(string $role): bool
    {
        if (!empty($this->roles)) {
            return in_array($role, $this->roles);
        }
        return $this->role === $role;
    }

    public function hasRole(string $role): bool
    {
        return $this->isRole($role);
    }

    /**
     * Get the user's initials from their name
     */
    public function getInitials(): string
    {
        $nama = $this->nama ?? $this->name ?? 'U';
        $words = explode(' ', trim($nama));
        
        if (count($words) >= 2) {
            // Get first letter of first and last word
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        
        // Get first two letters if single word
        return strtoupper(substr($nama, 0, min(2, strlen($nama))));
    }

    /**
     * Get the URL to the user's profile photo
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        // Generate default avatar with initials
        $initials = urlencode($this->getInitials());
        $name = urlencode($this->nama ?? $this->name ?? 'User');
        
        // Using UI Avatars API for generating avatar with initials
        // Background color: violet/purple theme, text color: white
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=7c3aed&color=ffffff&bold=true&format=svg";
    }

    /**
     * Delete the user's profile photo
     */
    public function deleteProfilePhoto(): void
    {
        if ($this->profile_photo_path) {
            Storage::disk('public')->delete($this->profile_photo_path);
            $this->forceFill(['profile_photo_path' => null])->save();
        }
    }

    /**
     * Update the user's profile photo
     */
    public function updateProfilePhoto($photo): void
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'profile_photo_path' => $photo->storePublicly('profile-photos', ['disk' => 'public']),
            ])->save();

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's signature (via employee)
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->employee && $this->employee->signature_path) {
            return $this->employee->signature_url;
        }

        return null;
    }

    /**
     * Update the user's signature (delegates to employee)
     */
    public function updateSignature($signature): void
    {
        if ($this->employee) {
            $this->employee->updateSignature($signature);
        }
    }

    /**
     * Delete the user's signature (delegates to employee)
     */
    public function deleteSignature(): void
    {
        if ($this->employee) {
            $this->employee->deleteSignature();
        }
    }

    /**
     * Get signature path (for compatibility)
     */
    public function getSignaturePathAttribute(): ?string
    {
        return $this->employee?->signature_path;
    }
    /**
     * Check if the user has any active leave requests (draft, pending, or deferred)
     */
    public function hasActiveLeaveRequests(): bool
    {
        if (!$this->employee) {
            return false;
        }

        return $this->employee->leaveRequests()
            ->whereIn('status', [
                'draft', 
                'menunggu_atasan_langsung', 
                'menunggu_atasan_tidak_langsung', 
                'perubahan', 
                'ditangguhkan'
            ])
            ->exists();
    }

    /**
     * Check if the user has any pending approvals to handle (as Atasan Langsung or Tidak Langsung)
     */
    public function hasPendingApprovals(): bool
    {
        // Check as Atasan Langsung
        $pendingLangsung = LeaveRequest::where('status', 'menunggu_atasan_langsung')
            ->whereHas('employee', function($q) {
                $q->where('atasan_langsung_id', $this->id);
            })->exists();
            
        if ($pendingLangsung) return true;

        // Check as Atasan Tidak Langsung
        $pendingTidakLangsung = LeaveRequest::where('status', 'menunggu_atasan_tidak_langsung')
            ->whereHas('employee', function($q) {
                $q->where('atasan_tidak_langsung_id', $this->id);
            })->exists();

        return $pendingTidakLangsung;
    }
}
