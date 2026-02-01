<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'max_days',
        'deduct_balance',
    ];

    protected function casts(): array
    {
        return [
            'deduct_balance' => 'boolean',
            'max_days' => 'integer',
        ];
    }
}