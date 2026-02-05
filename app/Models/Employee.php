<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jabatan',
        'golongan',
        'unit_kerja',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'atasan_langsung_id',
        'atasan_tidak_langsung_id',
    ];

    /* ================== RELATIONS ================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atasanLangsung()
    {
        return $this->belongsTo(User::class, 'atasan_langsung_id');
    }

    public function atasanTidakLangsung()
    {
        return $this->belongsTo(User::class, 'atasan_tidak_langsung_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
