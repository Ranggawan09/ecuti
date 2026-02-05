<?php

class DashboardController extends Controller
{
    public function index()
    {
        $approved = LeaveRequest::where('status', 'disetujui')->count();

        return view('kepegawaian.dashboard', compact('approved'));
    }
}
