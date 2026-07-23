<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Flow “Pinjam Buku” untuk guest harus menuju halaman Register.
            // Gunakan route membership.register yang memang aktif di project ini.
            return redirect()->route('membership.register')
                ->withErrors(['membership' => 'Silakan daftar terlebih dahulu.'])
                ->withInput();

        }

        // Membership hanya berlaku untuk user biasa. Admin harus lolos.
        if (Auth::user()?->role === 'admin') {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user?->hasPremiumAccess()) {
            Log::info('[MEMBERSHIP BLOCK] membership.active', [
                'email' => $user?->email,
                'role' => $user?->role,
                'membership_status' => $user?->membership_status,
                'has_premium_access' => $user?->hasPremiumAccess(),
                'path' => $request->path(),
            ]);

            return redirect()->route('login')
                ->withErrors(['membership' => 'Akun Anda sedang menunggu persetujuan Administrator.'])
                ->withInput();
        }

        return $next($request);
    }
}





