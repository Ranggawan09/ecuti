<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', //id pegawai
        'leave_type_id', //jenis cuti
        'year', //tahun
        'total_days', //total cuti yang diterima
        'used_days', //cuti yang digunakan
        'remaining_days', //sisa cuti
        'carried_over_days', //ditangguhkan
    ];

    /**
     * Get the employee that owns the leave balance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave type associated with the balance.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /* ================== BUSINESS LOGIC ================== */

    public function deduct(int $days): void
    {
        $this->used_days += $days;
        $this->remaining_days -= $days;
        $this->save();
    }
}
