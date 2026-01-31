<?php

class DashboardController extends Controller
{
    public function index()
    {
        $totalCuti = auth()->user()->employee
            ->leaveRequests()
            ->count();

        return view('pegawai.dashboard', compact('totalCuti'));
    }
}
