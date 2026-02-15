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
     */
    public function print(LeaveRequest $leaveRequest)
    {
        // Update printed_at timestamp
        $leaveRequest->update(['printed_at' => now()]);

        // Load all necessary relationships
        $leaveRequest->load([
            'employee.user',
            'employee.atasanLangsung',
            'employee.atasanTidakLangsung',
            'leaveType',
            'approvals.approver',
            'approvalAtasanLangsung.approver',
            'approvalAtasanTidakLangsung.approver'
        ]);

        return view('pages.kepegawaian.leave_requests.print', compact('leaveRequest'));
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
