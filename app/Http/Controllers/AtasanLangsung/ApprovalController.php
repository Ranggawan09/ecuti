<?php

namespace App\Http\Controllers\AtasanLangsung;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::where('status', 'menunggu_atasan_langsung')
            ->whereHas('employee', function ($q) {
                $q->where('atasan_langsung_id', auth()->user()->employee->id);
            })->get();

        return view('atasan_langsung.approvals.index', compact('leaveRequests'));
    }

    public function approve(
        LeaveRequest $leaveRequest,
        ApprovalService $service,
        Request $request
    ) {
        $this->authorize('approve', $leaveRequest);

        $service->approve($leaveRequest, auth()->user(), $request->note);

        return back()->with('success', 'Cuti disetujui');
    }

    public function reject(
        LeaveRequest $leaveRequest,
        ApprovalService $service,
        Request $request
    ) {
        $this->authorize('reject', $leaveRequest);

        $service->reject($leaveRequest, auth()->user(), $request->note);

        return back()->with('success', 'Cuti ditolak');
    }
}
