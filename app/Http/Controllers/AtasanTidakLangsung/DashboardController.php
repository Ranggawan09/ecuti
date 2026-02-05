<?php

class DashboardController extends Controller
{
    public function index()
    {
        $pending = LeaveRequest::where('status', 'menunggu_atasan_tidak_langsung')->count();

        return view('atasan_tidak_langsung.dashboard', compact('pending'));
    }
}
