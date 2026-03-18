<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function index()
    {
        // Surat yang sudah dicetak, urut dari terbaru
        $printedRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereNotNull('printed_at')
            ->orderByDesc('printed_at')
            ->get();

        // Info surat terbaru (no urut & nomor surat terakhir)
        $latestPrinted = $printedRequests->first();

        return view('pages.kepegawaian.dashboard', compact('printedRequests', 'latestPrinted'));
    }
}
