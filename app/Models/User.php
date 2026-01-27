<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nip',
        'email',
        'phone_number',
        'password',
        'role',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
        return $this->role === $role;
    }
}
