<?php

class DashboardController extends Controller
{
    public function index()
    {
        $pending = LeaveRequest::where('status', 'menunggu_atasan_langsung')->count();

        return view('atasan_langsung.dashboard', compact('pending'));
    }
}
