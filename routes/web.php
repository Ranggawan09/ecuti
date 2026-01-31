<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::redirect('/', 'login');
Route::middleware(['auth:sanctum'])->group(function () {

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
| KEPEGAWAIAN (HR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepegawaian'])
    ->prefix('kepegawaian')
    ->as('kepegawaian.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('pages.kepegawaian.dashboard'))
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

        Route::get('/dashboard', fn() => view('pages.admin.dashboard.dashboard'))
            ->name('dashboard');

        Route::get('/leave-types', fn() => view('pages.admin.leave-types.index'))
            ->name('leave-types');

        Route::get('/users', fn() => view('pages.admin.users.index'))
            ->name('users');


        

        // Route untuk pengajuan dengan role admin
        Route::post('/pengajuan/{id}/approve', [AdminController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [AdminController::class, 'reject'])->name('pengajuan.reject');
        Route::post('/admin/pengajuan/update/{id}', [AdminController::class, 'update'])->name('admin.pengajuan.update');
        Route::post('/admin/pengajuan/updateProgress/{id}', [AdminController::class, 'updateProgress'])->name('admin.pengajuan.updateProgress');
        Route::get('/admin/pengajuan/getProgress/{id}', [AdminController::class, 'getProgress'])->name('admin.pengajuan.getProgress');
        Route::post('/admin/simpan-ke-riwayat/{id}', [AdminController::class, 'simpanKeRiwayat'])->name('admin.simpanKeRiwayat');

        // Route pengajuan daftar dan tindak lanjut
        Route::get('/admin/pengajuan/daftar-pengajuan', [AdminController::class, 'daftarPengajuan'])->name('admin.daftarPengajuan');
        Route::get('/admin/pengajuan/tindak-lanjut', [AdminController::class, 'tindakLanjut'])->name('admin.tindakLanjut');

        // Route riwayat
        Route::get('/admin/riwayat', [AdminController::class, 'riwayat'])->name('admin.riwayat');
        Route::get('/admin/riwayat/detail-riwayat/{id}', [AdminController::class, 'detail_riwayat'])->name('admin.detail.riwayat');
        Route::get('/admin/riwayat/{id}/print', [AdminController::class, 'print'])->name('admin.pengajuan.print');

        // Route detail tindak lanjut
        Route::get('/admin/pengajuan/tindak-lanjut/detail-tindak-lanjut/{id}', [AdminController::class, 'detail'])->name('admin.detail.tindakLanjut');

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