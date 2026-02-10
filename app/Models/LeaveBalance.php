<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'total_days',
        'used_days',
        'remaining_days',
    ];

    /* ================== RELATIONS ================== */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /* ================== BUSINESS LOGIC ================== */

    public function deduct(int $days): void
    {
        $this->used_days += $days;
        $this->remaining_days -= $days;
        $this->save();
    }
}
