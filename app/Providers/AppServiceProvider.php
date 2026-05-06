<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pengajuan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mocking year 2027 for testing leave balance feature
        //\Illuminate\Support\Carbon::setTestNow('2027-01-01');

        View::composer('*', function ($view) {
            $pendingCount = LeaveRequest::where('status', 'draft')->count();
            $view->with('pendingCount', $pendingCount);
        });
    }
}
