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

        // Approval rate (persentase disetujui dari total)
        $approvalRate = $stats['total'] > 0 
            ? round(($stats['disetujui'] / $stats['total']) * 100, 1) 
            : 0;

        // Statistik bulanan tahun ini
        $currentYear = now()->year;
        $monthlyCounts = LeaveRequest::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, count(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyCounts[$i] ?? 0;
        }

        // Statistik tahunan 3 tahun terakhir
        $yearlyCounts = LeaveRequest::selectRaw('YEAR(created_at) as year, count(*) as count')
            ->where('created_at', '>=', now()->subYears(2)->startOfYear())
            ->groupBy('year')
            ->pluck('count', 'year')
            ->toArray();

        $yearlyData = [];
        $yearlyLabels = [];
        for ($i = 2; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $yearlyLabels[] = (string)$year;
            $yearlyData[] = $yearlyCounts[$year] ?? 0;
        }

        return view('pages.kepegawaian.dashboard', compact(
            'stats',
            'belumDicetak',
            'printedRequests',
            'latestPrinted',
            'recentRequests',
            'approvalRate',
            'monthlyData',
            'yearlyData',
            'yearlyLabels'
        ));
    }
}
