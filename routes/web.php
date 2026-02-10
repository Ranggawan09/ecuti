<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pegawai\PengajuanCutiController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::redirect('/', 'login');
Route::middleware(['auth:sanctum'])->group(function () {

/*
|--------------------------------------------------------------------------
| KEPEGAWAIAN (HR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepegawaian'])
    ->prefix('kepegawaian')
    ->as('kepegawaian.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.kepegawaian.dashboard'))
            ->name('dashboard');

        // Export route must be before resource routes
        Route::get('leave-requests/export', 
            [\App\Http\Controllers\Kepegawaian\LeaveRequestController::class, 'export']
        )->name('leave-requests.export');

        // Leave Requests CRUD
        Route::resource('leave-requests', 
            \App\Http\Controllers\Kepegawaian\LeaveRequestController::class
        );

        // Master Leave Type
        Route::resource('leave-types', 
            \App\Http\Controllers\Kepegawaian\LeaveTypeController::class
        );

        // Leave Balance
        Route::resource('leave-balances', 
            \App\Http\Controllers\Kepegawaian\LeaveBalanceController::class
        )->only(['index', 'store', 'update']);
    });

/*
|--------------------------------------------------------------------------
| PEGAWAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pegawai'])
    ->prefix('pegawai')
    ->as('pegawai.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.pegawai.dashboard'))
            ->name('dashboard');

        // Leave Requests
        Route::resource('leave-requests', 
            \App\Http\Controllers\Pegawai\LeaveRequestController::class
        );

        // Leave Balance
        Route::get('leave-balances', 
            [\App\Http\Controllers\Pegawai\LeaveBalanceController::class, 'index']
        )->name('leave-balances.index');
    });

/*
|--------------------------------------------------------------------------
| ATASAN LANGSUNG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:atasan_langsung'])
    ->prefix('atasan-langsung')
    ->as('atasan-langsung.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.atasan_langsung.dashboard'))
            ->name('dashboard');

        // Approval
        Route::get('approvals', 
            [\App\Http\Controllers\AtasanLangsung\ApprovalController::class, 'index']
        )->name('approvals.index');

        Route::post('approvals/{leaveRequest}/approve', 
            [\App\Http\Controllers\AtasanLangsung\ApprovalController::class, 'approve']
        )->name('approvals.approve');

        Route::post('approvals/{leaveRequest}/reject', 
            [\App\Http\Controllers\AtasanLangsung\ApprovalController::class, 'reject']
        )->name('approvals.reject');
    });

/*
|--------------------------------------------------------------------------
| ATASAN TIDAK LANGSUNG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:atasan_tidak_langsung'])
    ->prefix('atasan-tidak-langsung')
    ->as('atasan-tidak-langsung.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.atasan_tidak_langsung.dashboard'))
            ->name('dashboard');

        Route::get('approvals', 
            [\App\Http\Controllers\AtasanTidakLangsung\ApprovalController::class, 'index']
        )->name('approvals.index');

        Route::post('approvals/{leaveRequest}/approve', 
            [\App\Http\Controllers\AtasanTidakLangsung\ApprovalController::class, 'approve']
        )->name('approvals.approve');

        Route::post('approvals/{leaveRequest}/reject', 
            [\App\Http\Controllers\AtasanTidakLangsung\ApprovalController::class, 'reject']
        )->name('approvals.reject');
    });

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.admin.dashboard.dashboard'))
            ->name('dashboard');

        // export harus di atas resource, kalau di bawah akan ditangkap sebagai show({id} = 'export')
        Route::get('users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])
            ->name('users.export');

        Route::get('leave-types/export', [App\Http\Controllers\Admin\LeaveTypeController::class, 'export'])
            ->name('leave-types.export');

        Route::resource('leave-types', App\Http\Controllers\Admin\LeaveTypeController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // User & Employee
        Route::resource('users', 
            \App\Http\Controllers\Admin\UserController::class
        );

        Route::resource('employees', 
            \App\Http\Controllers\Admin\EmployeeController::class
        );

        // Logs
        Route::get('activity-logs', 
            [\App\Http\Controllers\Admin\ActivityLogController::class, 'index']
        )->name('activity-logs.index');
    });

});