<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/loginStaff')
                ->withErrors(['login' => 'Silakan login untuk melanjutkan']);
        }

        if (auth()->user()->role !== 'Staff') {
            return redirect('/loginStaff')
                ->withErrors(['login' => 'Hanya Staff yang bisa mengakses halaman ini']);
        }

        return $next($request);
    }
}
