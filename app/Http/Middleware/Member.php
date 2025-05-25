<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Member
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/loginMember')
                ->withErrors(['login' => 'Silakan login untuk melanjutkan']);
        }

        if (auth()->user()->role !== 'Member') {
            return redirect('/loginMember')
                ->withErrors(['login' => 'Hanya Member yang bisa mengakses halaman ini']);
        }

        return $next($request);
    }
}
