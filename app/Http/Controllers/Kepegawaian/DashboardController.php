<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik keseluruhan pengajuan cuti
        $stats = [
            'total'           => LeaveRequest::count(),
            'menunggu'        => LeaveRequest::whereIn('status', [
                                    'menunggu_atasan_langsung',
                                    'menunggu_atasan_tidak_langsung',
                                ])->count(),
            'disetujui'       => LeaveRequest::where('status', 'disetujui')->count(),
            'tidak_disetujui' => LeaveRequest::where('status', 'tidak_disetujui')->count(),
            'total_pegawai'   => Employee::count(),
        ];

        // Pengajuan yang sudah disetujui penuh namun belum dicetak
        $belumDicetak = LeaveRequest::where('status', 'disetujui')
            ->whereNull('printed_at')
            ->count();

        // Surat yang sudah dicetak, urut dari terbaru
        $printedRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereNotNull('printed_at')
            ->orderByDesc('printed_at')
            ->get();

        // Info surat terbaru (no urut & nomor surat terakhir)
        $latestPrinted = $printedRequests->first();

        // 5 pengajuan terbaru (semua status)
        $recentRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.kepegawaian.dashboard', compact(
            'stats',
            'belumDicetak',
            'printedRequests',
            'latestPrinted',
            'recentRequests'
        ));
    }
}
