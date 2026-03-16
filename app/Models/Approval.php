<?php
// app/Models/Approval.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'approver_id',
        'level',
        'status',
        'note',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    // Relasi ke LeaveRequest
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    // Relasi ke Approver (User)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}