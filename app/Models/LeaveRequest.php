<?php
// app/Models/LeaveRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'address_during_leave',
        'status',
        'printed_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'printed_at' => 'datetime',
    ];

    // Relasi ke Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relasi ke User melalui Employee
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Employee::class,
            'id', // Foreign key on employees table
            'id', // Foreign key on users table
            'employee_id', // Local key on leave_requests table
            'user_id' // Local key on employees table
        );
    }

    // Relasi ke LeaveType
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    // Relasi ke Approval
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    // Approval untuk atasan langsung
    public function approvalAtasanLangsung()
    {
        return $this->hasOne(Approval::class)
                    ->where('level', 'atasan_langsung');
    }

    // Approval untuk atasan tidak langsung
    public function approvalAtasanTidakLangsung()
    {
        return $this->hasOne(Approval::class)
                    ->where('level', 'atasan_tidak_langsung');
    }

    // Helper untuk get status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => '<span class=\"badge bg-secondary\">Draft</span>',
            'menunggu_atasan_langsung' => '<span class=\"badge bg-warning\">Menunggu Atasan Langsung</span>',
            'menunggu_atasan_tidak_langsung' => '<span class=\"badge bg-info\">Menunggu Atasan Tidak Langsung</span>',
            'disetujui' => '<span class=\"badge bg-success\">Disetujui</span>',
            'ditolak' => '<span class=\"badge bg-danger\">Ditolak</span>',
            'ditangguhkan' => '<span class=\"badge bg-warning\">Ditangguhkan</span>',
            default => '<span class=\"badge bg-secondary\">-</span>'
        };
    }
}
