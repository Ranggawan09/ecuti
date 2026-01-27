<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::latest()->get();
        return view('kepegawaian.leave_requests.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('kepegawaian.leave_requests.show', compact('leaveRequest'));
    }
}
