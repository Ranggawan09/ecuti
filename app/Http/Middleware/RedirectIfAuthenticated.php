<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;
                
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'pegawai') {
                    return redirect()->route('pegawai.dashboard');
                } elseif ($role === 'atasan_langsung') {
                    return redirect()->route('atasan-langsung.dashboard');
                } elseif ($role === 'atasan_tidak_langsung') {
                    return redirect()->route('atasan-tidak-langsung.dashboard');
                } elseif ($role === 'kepegawaian') {
                    return redirect()->route('kepegawaian.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
