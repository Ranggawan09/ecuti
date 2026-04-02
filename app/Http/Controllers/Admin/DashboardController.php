<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // === STATISTIK GLOBAL ===
        $stats = [
            'total_pegawai'      => Employee::count(),
            'total_user'         => User::count(),
            'menunggu_al'        => LeaveRequest::where('status', 'menunggu_atasan_langsung')->count(),
            'menunggu_atl'       => LeaveRequest::where('status', 'menunggu_atasan_tidak_langsung')->count(),
            'disetujui'          => LeaveRequest::where('status', 'disetujui')->count(),
            'tidak_disetujui'    => LeaveRequest::where('status', 'tidak_disetujui')->count(),
            'ditangguhkan'       => LeaveRequest::where('status', 'ditangguhkan')->count(),
            'total_pengajuan'    => LeaveRequest::count(),
        ];

        // === DAFTAR SEMUA PEGAWAI ===
        $employees = Employee::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // === PENGAJUAN YANG MASIH MENUNGGU (5 terbaru) ===
        $pendingRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereIn('status', ['menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // === RIWAYAT PENGAJUAN TERBARU (10 terbaru) ===
        $recentRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages.admin.dashboard.dashboard', compact(
            'stats',
            'employees',
            'pendingRequests',
            'recentRequests'
        ));
    }
}
