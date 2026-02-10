<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Pegawai\LeaveRequestController as PegawaiLeaveRequestController;
use App\Http\Controllers\Api\AtasanLangsung\ApprovalController as AtasanLangsungApprovalController;
use App\Http\Controllers\Api\AtasanTidakLangsung\ApprovalController as AtasanTidakLangsungApprovalController;
use App\Http\Controllers\Api\Kepegawaian\LeaveRequestController as KepegawaianLeaveRequestController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| PEGAWAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {

    Route::get('/leave-requests', [PegawaiLeaveRequestController::class, 'index'])
        ->name('leave-requests.index');

    Route::post('/leave-requests', [PegawaiLeaveRequestController::class, 'store'])
        ->name('leave-requests.store');

    Route::get('/leave-requests/{leaveRequest}', [PegawaiLeaveRequestController::class, 'show'])
        ->name('leave-requests.show');
});

/*
|--------------------------------------------------------------------------
| ATASAN LANGSUNG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:atasan_langsung'])
    ->prefix('atasan-langsung')
    ->name('atasan-langsung.')
    ->group(function () {

        Route::get('/approvals', [AtasanLangsungApprovalController::class, 'index'])
            ->name('approvals.index');

        Route::post('/approvals/{leaveRequest}/approve', [AtasanLangsungApprovalController::class, 'approve'])
            ->name('approvals.approve');

        Route::post('/approvals/{leaveRequest}/reject', [AtasanLangsungApprovalController::class, 'reject'])
            ->name('approvals.reject');
    });

/*
|--------------------------------------------------------------------------
| ATASAN TIDAK LANGSUNG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:atasan_tidak_langsung'])
    ->prefix('atasan-tidak-langsung')
    ->name('atasan-tidak-langsung.')
    ->group(function () {

        Route::get('/approvals', [AtasanTidakLangsungApprovalController::class, 'index'])
            ->name('approvals.index');

        Route::post('/approvals/{leaveRequest}/approve', [AtasanTidakLangsungApprovalController::class, 'approve'])
            ->name('approvals.approve');

        Route::post('/approvals/{leaveRequest}/reject', [AtasanTidakLangsungApprovalController::class, 'reject'])
            ->name('approvals.reject');
    });

/*
|--------------------------------------------------------------------------
| KEPEGAWAIAN (HR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:kepegawaian'])
    ->prefix('kepegawaian')
    ->name('kepegawaian.')
    ->group(function () {

        Route::get('/leave-requests', [KepegawaianLeaveRequestController::class, 'index'])
            ->name('leave-requests.index');

        Route::get('/leave-requests/{leaveRequest}', [KepegawaianLeaveRequestController::class, 'show'])
            ->name('leave-requests.show');
    });

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');
    });
