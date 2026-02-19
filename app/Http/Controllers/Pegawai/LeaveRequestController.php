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
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType', 'approvals.approver'])
            ->whereHas('employee', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        // Transform data for JavaScript
        $leaveRequestsData = $leaveRequests->map(function($leave) {
            return [
                'id' => $leave->id,
                'leave_type_name' => $leave->leaveType->name ?? '-',
                'start_date' => $leave->start_date->format('Y-m-d'),
                'start_date_formatted' => $leave->start_date->format('d M Y'),
                'end_date' => $leave->end_date->format('Y-m-d'),
                'end_date_formatted' => $leave->end_date->format('d M Y'),
                'total_days' => $leave->total_days,
                'status' => $leave->status,
            ];
        });

        return view('pages.pegawai.leave_requests.index', compact('leaveRequests', 'leaveRequestsData'));
    }

    public function create()
    {
        $employee = auth()->user()->employee;
        
        // Check if employee exists
        if (!$employee) {
            return redirect()->route('profile.show')
                ->with('warning', 'Data pegawai tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
        }
        
        // Load user relationship for signature check
        $employee->load('user');
        
        // Check if profile is complete
        if (!$employee->hasCompleteProfile()) {
            $missingFields = $employee->getMissingProfileFields();
            return redirect()->route('profile.show')
                ->with('warning', 'Profil Anda belum lengkap. Silakan lengkapi data berikut terlebih dahulu: ' . implode(', ', $missingFields));
        }
        
        $leaveTypes = LeaveType::all();
        
        return view('pages.pegawai.leave_requests.create', compact('employee', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        // Get employee record for the authenticated user
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('profile.show')
                ->with('warning', 'Data pegawai tidak ditemukan. Silakan hubungi administrator.')
                ->withErrors(['error' => 'Data pegawai tidak ditemukan.']);
        }
        
        // Load user relationship for signature check
        $employee->load('user');
        
        // Check if profile is complete
        if (!$employee->hasCompleteProfile()) {
            $missingFields = $employee->getMissingProfileFields();
            return redirect()->route('profile.show')
                ->with('warning', 'Profil Anda belum lengkap. Silakan lengkapi data berikut terlebih dahulu: ' . implode(', ', $missingFields));
        }
        
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'address_during_leave' => 'required|string',
        ]);

        // Calculate total days
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalDays = $startDate->diff($endDate)->days + 1;

        // Create leave request with auto-set employee_id and status
        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'address_during_leave' => $validated['address_during_leave'],
            'status' => 'menunggu_atasan_langsung',
        ]);

        return redirect()
            ->route('pegawai.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        $leaveRequest->load(['employee.user', 'leaveType', 'approvals.approver']);

        return view('pages.pegawai.leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        
        $employee = auth()->user()->employee;
        $leaveTypes = LeaveType::all();
        
        return view('pages.pegawai.leave_requests.edit', compact('leaveRequest', 'employee', 'leaveTypes'));
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

        // Calculate total days
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalDays = $startDate->diff($endDate)->days + 1;

        $validated['total_days'] = $totalDays;

        $leaveRequest->update($validated);

        return redirect()->route('pegawai.leave-requests.index')
            ->with('success', 'Data cuti berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('pegawai.leave-requests.index')
            ->with('success', 'Data cuti berhasil dihapus.');
    }
}
