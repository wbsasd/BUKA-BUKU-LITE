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
            $user = Auth::user();

            // Membership hanya berlaku untuk user biasa. Admin harus lolos.
            if ($user?->role === 'admin') {
                return $next($request);
            }

            if (!$user?->hasPremiumAccess()) {
                Log::info('[MEMBERSHIP BLOCK LOGIN] membership.active.login', [
                    'email' => $user?->email,
                    'role' => $user?->role,
                    'membership_status' => $user?->membership_status,
                    'has_premium_access' => $user?->hasPremiumAccess(),
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



