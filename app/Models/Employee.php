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
        'masa_kerja_bulan'
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
}