<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::redirect('/', 'login');

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

        Route::get('/dashboard', fn() => view('atasan_langsung.dashboard'))
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

        Route::get('/dashboard', fn() => view('atasan_tidak_langsung.dashboard'))
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
| KEPEGAWAIAN (HR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepegawaian'])
    ->prefix('kepegawaian')
    ->as('kepegawaian.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('kepegawaian.dashboard'))
            ->name('dashboard');

        Route::get('leave-requests', 
            [\App\Http\Controllers\Kepegawaian\LeaveRequestController::class, 'index']
        )->name('leave-requests.index');

        Route::get('leave-requests/{leaveRequest}', 
            [\App\Http\Controllers\Kepegawaian\LeaveRequestController::class, 'show']
        )->name('leave-requests.show');

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
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard'))
            ->name('dashboard');

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
