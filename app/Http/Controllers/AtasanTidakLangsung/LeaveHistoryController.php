<?php
// app/Http/Controllers/AtasanTidakLangsung/LeaveHistoryController.php

namespace App\Http\Controllers\AtasanTidakLangsung;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveHistoryController extends Controller
{
    public function index()
    {
        // Ambil employee_id dari users yang atasan tidak langsungnya adalah user yang login
        $employeeIds = Employee::where('atasan_tidak_langsung_id', Auth::id())
            ->pluck('id');

        // Ambil leave requests dari employees tersebut yang sudah diproses (bukan menunggu)
        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType', 'approvals.approver'])
            ->whereIn('employee_id', $employeeIds)
            ->whereNotIn('status', ['menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.atasan_tidak_langsung.leave_history.index', compact('leaveRequests'));
    }
}
