<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jabatan',
        'golongan',
        'unit_kerja',
        'atasan_langsung_id',
        'atasan_tidak_langsung_id',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'signature_path'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Atasan Langsung
    public function atasanLangsung()
    {
        return $this->belongsTo(User::class, 'atasan_langsung_id');
    }

    // Relasi ke Atasan Tidak Langsung
    public function atasanTidakLangsung()
    {
        return $this->belongsTo(User::class, 'atasan_tidak_langsung_id');
    }

    // Relasi ke Leave Request
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Relasi ke Leave Balance
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get the URL to the employee's signature
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->signature_path) {
            return asset('storage/' . $this->signature_path);
        }

        return null;
    }

    /**
     * Update the employee's signature
     */
    public function updateSignature($signature): void
    {
        $oldPath = $this->signature_path;
        
        $this->forceFill([
            'signature_path' => $signature->storePublicly('signatures', ['disk' => 'public']),
        ])->save();

        if ($oldPath) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }
    }

    /**
     * Delete the employee's signature
     */
    public function deleteSignature(): void
    {
        if ($this->signature_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->signature_path);
            $this->forceFill(['signature_path' => null])->save();
        }
    }

    /**
     * Check if employee profile is complete for leave request
     */
    public function hasCompleteProfile(): bool
    {
        return !empty($this->jabatan) 
            && !empty($this->unit_kerja) 
            && !empty($this->golongan)
            && ($this->masa_kerja_tahun !== null || $this->masa_kerja_bulan !== null)
            && !empty($this->user->signature_path);
    }

    /**
     * Get missing profile fields
     */
    public function getMissingProfileFields(): array
    {
        $missing = [];
        
        if (empty($this->jabatan)) {
            $missing[] = 'Jabatan';
        }
        if (empty($this->unit_kerja)) {
            $missing[] = 'Unit Kerja';
        }
        if (empty($this->golongan)) {
            $missing[] = 'Golongan Ruang';
        }
        if ($this->masa_kerja_tahun === null && $this->masa_kerja_bulan === null) {
            $missing[] = 'Masa Kerja';
        }
        if (empty($this->user->signature_path)) {
            $missing[] = 'Foto Tanda Tangan';
        }
        
        return $missing;
    }
}