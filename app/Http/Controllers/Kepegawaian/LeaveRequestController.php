<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeaveRequestsExport;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereNull('printed_at')
            ->latest()
            ->get();

        return view('pages.kepegawaian.leave_requests.index', compact('leaveRequests'));
    }



    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee.user', 'leaveType', 'approvals.approver']);

        return view('pages.kepegawaian.leave_requests.show', compact('leaveRequest'));
    }



    /**
     * Export leave requests data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');

        if ($format === 'pdf') {
            return $this->exportPdf();
        }

        return $this->exportExcel();
    }

    /**
     * Print individual leave request form
     * Auto-assign no_urut dan generate nomor_surat saat pertama kali dicetak
     */
    public function print(LeaveRequest $leaveRequest)
    {
        // Hitung no_urut: ambil no_urut terbesar dari tahun ini + 1
        if (is_null($leaveRequest->no_urut)) {
            $tahunIni = now()->year;
            $noUrutTerakhir = LeaveRequest::whereNotNull('no_urut')
                ->whereYear('printed_at', $tahunIni)
                ->max('no_urut') ?? 0;
            $noUrut = $noUrutTerakhir + 1;

            // Generate nomor surat otomatis
            $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
            $bln = (int) now()->format('n');
            $nomorSurat = $noUrut . '/KPN.W14.U5/KP5.3/' . $bulanRomawi[$bln - 1] . '/' . $tahunIni;

            $leaveRequest->update([
                'printed_at'   => now(),
                'no_urut'      => $noUrut,
                'nomor_surat'  => $nomorSurat,
            ]);
        } else {
            // Sudah pernah dicetak sebelumnya, update printed_at saja
            $leaveRequest->update(['printed_at' => now()]);
        }

        // Load all necessary relationships
        $leaveRequest->load([
            'employee.user',
            'employee.leaveBalances',
            'employee.atasanLangsung.employee',
            'employee.atasanTidakLangsung.employee',
            'leaveType',
            'approvals.approver',
            'approvalAtasanLangsung.approver',
            'approvalAtasanTidakLangsung.approver'
        ]);

        return view('pages.kepegawaian.leave_requests.print', compact('leaveRequest'));
    }

    /**
     * Update no_urut dan nomor_surat secara manual
     */
    public function updateLetterNumber(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'no_urut'     => 'required|integer|min:1',
            'nomor_surat' => 'required|string|max:255',
        ]);

        $leaveRequest->update($validated);

        return response()->json([
            'success'     => true,
            'no_urut'     => $leaveRequest->no_urut,
            'nomor_surat' => $leaveRequest->nomor_surat,
            'message'     => 'Nomor surat berhasil diperbarui.',
        ]);
    }

    /**
     * Display leave request history (printed requests only)
     */
    public function history()
    {
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->whereNotNull('printed_at')
            ->latest('printed_at')
            ->get();

        return view('pages.kepegawaian.leave_requests.history', compact('leaveRequests'));
    }

    /**
     * Export to Excel
     */
    private function exportExcel()
    {
        return Excel::download(new LeaveRequestsExport, 'leave-requests-' . date('Y-m-d') . '.xlsx');
    }

    private function exportPdf()
    {
        try {
            // Increase memory limit and execution time for PDF generation
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', '300');

            $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pdf = Pdf::loadView('pages.kepegawaian.leave_requests.pdf', compact('leaveRequests'))
                ->setPaper('a4', 'landscape')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true)
                ->setOption('defaultFont', 'Arial');

            return $pdf->download('leave-requests-' . date('Y-m-d') . '.pdf');
        }
        catch (\Exception $e) {
            \Log::error('PDF Export Error (Leave Requests): ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF. Error: ' . $e->getMessage());
        }
    }
}
