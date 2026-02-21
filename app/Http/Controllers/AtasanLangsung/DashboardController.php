<?php
// app/Http/Controllers/AtasanLangsung/DashboardController.php

namespace App\Http\Controllers\AtasanLangsung;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil employee_id dari users yang atasan langsungnya adalah user yang login
        $employeeIds = Employee::where('atasan_langsung_id', Auth::id())
            ->pluck('id');

        $stats = [
            'menunggu' => LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'menunggu_atasan_langsung')
            ->count(),
            'disetujui' => LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'disetujui')
            ->count(),
            'tidak_disetujui' => LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'tidak_disetujui')
            ->count(),
        ];

        return view('pages.atasan_langsung.dashboard', compact('stats'));
    }
}