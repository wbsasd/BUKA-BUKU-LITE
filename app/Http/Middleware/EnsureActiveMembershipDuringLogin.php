<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembershipDuringLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware is intended to be attached to routes that require auth.
        // For pending/rejected users, we block the request.
        if (Auth::check() && Auth::user()?->membership_status !== 'active') {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['membership' => 'Akun Anda sedang menunggu persetujuan Administrator.'])
                ->withInput();
        }

        return $next($request);
    }
}

