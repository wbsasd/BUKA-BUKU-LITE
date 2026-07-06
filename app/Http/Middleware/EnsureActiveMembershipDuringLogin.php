<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembershipDuringLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware is intended to be attached to routes that require auth.
        // For pending/rejected users, we block the request.
        if (Auth::check()) {
            // Membership hanya berlaku untuk user biasa. Admin harus lolos.
            if (Auth::user()?->role === 'admin') {
                return $next($request);
            }

            if (Auth::user()?->membership_status !== 'active') {
                Log::info('[MEMBERSHIP BLOCK LOGIN] membership.active.login', [
                    'email' => Auth::user()?->email,
                    'role' => Auth::user()?->role,
                    'membership_status' => Auth::user()?->membership_status,
                    'path' => $request->path(),
                ]);

                Auth::logout();

                return redirect()->route('login')
                    ->withErrors(['membership' => 'Akun Anda sedang menunggu persetujuan Administrator.'])
                    ->withInput();
            }
        }

        return $next($request);
    }
}



