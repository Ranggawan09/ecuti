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
            ->latest()
            ->get();
        
        return view('pages.kepegawaian.leave_requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::with('user')->get();
        $leaveTypes = LeaveType::all();
        
        return view('pages.kepegawaian.leave_requests.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer|min:1',
            'reason' => 'required|string',
            'address_during_leave' => 'required|string|max:255',
            'status' => 'required|in:draft,menunggu_atasan_langsung,menunggu_atasan_tidak_langsung,disetujui,ditolak,ditangguhkan',
        ]);

        LeaveRequest::create($validated);

        return redirect()->route('kepegawaian.leave-requests.index')
            ->with('success', 'Data cuti pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee.user', 'leaveType', 'approvals.user']);
        
        return view('pages.kepegawaian.leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::with('user')->get();
        $leaveTypes = LeaveType::all();
        
        return view('pages.kepegawaian.leave_requests.edit', compact('leaveRequest', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer|min:1',
            'reason' => 'required|string',
            'address_during_leave' => 'required|string|max:255',
            'status' => 'required|in:draft,menunggu_atasan_langsung,menunggu_atasan_tidak_langsung,disetujui,ditolak,ditangguhkan',
        ]);

        $leaveRequest->update($validated);

        return redirect()->route('kepegawaian.leave-requests.index')
            ->with('success', 'Data cuti pegawai berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('kepegawaian.leave-requests.index')
            ->with('success', 'Data cuti pegawai berhasil dihapus.');
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
        } catch (\Exception $e) {
            \Log::error('PDF Export Error (Leave Requests): ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF. Error: ' . $e->getMessage());
        }
    }
}
