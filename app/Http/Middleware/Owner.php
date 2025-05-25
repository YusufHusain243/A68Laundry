<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/loginOwner')
                ->withErrors(['login' => 'Silakan login untuk melanjutkan']);
        }

        if (auth()->user()->role !== 'Owner') {
            return redirect('/loginOwner')
                ->withErrors(['login' => 'Hanya Owner yang bisa mengakses halaman ini']);
        }

        return $next($request);
    }
}
