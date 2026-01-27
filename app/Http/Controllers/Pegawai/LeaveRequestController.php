<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::whereHas('employee', function ($q) {
            $q->where('user_id', auth()->id());
        })->latest()->get();

        return view('pegawai.leave_requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('pegawai.leave_requests.create');
    }

    public function store(Request $request, LeaveRequestService $service)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'address_during_leave' => 'required|string',
        ]);

        $service->create($request->all(), auth()->user());

        return redirect()
            ->route('pegawai.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        return view('pegawai.leave_requests.show', compact('leaveRequest'));
    }
}
