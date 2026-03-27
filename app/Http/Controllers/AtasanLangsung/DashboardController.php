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
        // Ambil semua pegawai yang atasan langsungnya adalah user yang login
        $employees = Employee::with('user')
            ->where('atasan_langsung_id', Auth::id())
            ->get();

        $employeeIds = $employees->pluck('id');

        // Statistik pengajuan cuti bawahan
        $stats = [
            'menunggu'       => LeaveRequest::whereIn('employee_id', $employeeIds)
                ->where('status', 'menunggu_atasan_langsung')
                ->count(),
            'disetujui'      => LeaveRequest::whereIn('employee_id', $employeeIds)
                ->where('status', 'disetujui')
                ->count(),
            'tidak_disetujui' => LeaveRequest::whereIn('employee_id', $employeeIds)
                ->where('status', 'tidak_disetujui')
                ->count(),
            'total_pegawai'  => $employees->count(),
        ];

        // Pengajuan terbaru yang menunggu persetujuan (5 teratas)
        $pendingRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'menunggu_atasan_langsung')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Riwayat pengajuan terbaru dari semua bawahan (5 teratas)
        $recentRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereIn('employee_id', $employeeIds)
            ->whereNotIn('status', ['menunggu_atasan_langsung'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.atasan_langsung.dashboard', compact(
            'stats',
            'employees',
            'pendingRequests',
            'recentRequests'
        ));
    }
}