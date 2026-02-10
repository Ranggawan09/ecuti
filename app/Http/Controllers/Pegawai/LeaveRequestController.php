<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::whereHas('employee', function ($q) {
            $q->where('user_id', auth()->id());
        })->latest()->get();

        return view('pages.pegawai.leave_requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('pages.pegawai.leave_requests.create');
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
            ->route('pages.pegawai.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        return view('pages.pegawai.leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        
        $employees = Employee::with('user')->get();
        $leaveTypes = LeaveType::all();
        
        return view('pages.pegawai.leave_requests.edit', compact('leaveRequest', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'address_during_leave' => 'required|string',
        ]);

        $leaveRequest->update($validated);

        return redirect()->route('pegawai.leave-requests.index')
            ->with('success', 'Data cuti pegawai berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('pegawai.leave-requests.index')
            ->with('success', 'Data cuti pegawai berhasil dihapus.');
    }
}
