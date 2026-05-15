<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'tmt_masa_kerja',
        'signature_path'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'atasan_langsung_id' => 'integer',
        'atasan_tidak_langsung_id' => 'integer',
        'masa_kerja_tahun' => 'integer',
        'masa_kerja_bulan' => 'integer',
        'tmt_masa_kerja' => 'date',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atasanLangsung()
    {
        return $this->belongsTo(User::class , 'atasan_langsung_id');
    }

    public function atasanTidakLangsung()
    {
        return $this->belongsTo(User::class , 'atasan_tidak_langsung_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /*
     |--------------------------------------------------------------------------
     | ACCESSORS
     |--------------------------------------------------------------------------
     */

    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_path
            ? asset('storage/' . $this->signature_path)
            : null;
    }

    /**
     * Get the dynamic masa kerja based on tmt_masa_kerja
     */
    public function getMasaKerjaAttribute(): object
    {
        if ($this->tmt_masa_kerja) {
            $diff = now()->diff($this->tmt_masa_kerja);
            return (object) [
                'tahun' => $diff->y,
                'bulan' => $diff->m,
            ];
        }

        // Fallback to legacy fields if tmt is missing
        return (object) [
            'tahun' => $this->masa_kerja_tahun ?? 0,
            'bulan' => $this->masa_kerja_bulan ?? 0,
        ];
    }

    /*
     |--------------------------------------------------------------------------
     | SIGNATURE MANAGEMENT
     |--------------------------------------------------------------------------
     */

    public function updateSignature($signature): void
    {
        $oldPath = $this->signature_path;

        $path = $signature->storePublicly('signatures', ['disk' => 'public']);

        $this->forceFill([
            'signature_path' => $path,
        ])->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }
    }

    public function deleteSignature(): void
    {
        if ($this->signature_path) {
            Storage::disk('public')->delete($this->signature_path);
            $this->forceFill(['signature_path' => null])->save();
        }
    }

    /*
     |--------------------------------------------------------------------------
     | BUSINESS RULES
     |--------------------------------------------------------------------------
     */

    public function hasCompleteProfile(): bool
    {
        return !empty($this->jabatan)
            && !empty($this->unit_kerja)
            && !empty($this->golongan)
            && ($this->masa_kerja_tahun !== null || $this->masa_kerja_bulan !== null)
            && !empty($this->signature_path);
    }

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

        if (empty($this->signature_path)) {
            $missing[] = 'Foto Tanda Tangan';
        }

        return $missing;
    }
    /**
     * Calculate available balance for a specific leave type
     * (Remaining + Carried Over) - Pending Requests
     */
    public function getAvailableLeaveBalance(int $leaveTypeId): int
    {
        $currentYear = now()->year;
        $leaveType = \App\Models\LeaveType::find($leaveTypeId);
        
        if (!$leaveType) return 0;

        // Cuti Tahunan uses a 3-year pool (N, N-1, N-2)
        if (str_contains(strtolower($leaveType->name), 'tahunan')) {
            $years = [$currentYear, $currentYear - 1, $currentYear - 2];
            $balances = $this->leaveBalances()
                ->where('leave_type_id', $leaveTypeId)
                ->whereIn('year', $years)
                ->get();
            $gross = $balances->sum('remaining_days');
        } else {
            // Other types usually use only the current year balance
            $balance = $this->leaveBalances()
                ->where('leave_type_id', $leaveTypeId)
                ->where('year', $currentYear)
                ->first();
            $gross = $balance ? $balance->remaining_days : 0;
        }

        $pending = $this->leaveRequests()
            ->where('leave_type_id', $leaveTypeId)
            ->whereIn('status', ['menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung'])
            ->sum('total_days');

        return max(0, $gross - $pending);
    }
}