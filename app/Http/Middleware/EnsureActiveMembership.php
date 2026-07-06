<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Flow “Pinjam Buku” untuk guest harus menuju halaman Register.
            // Di project ini, endpoint register menggunakan URL: /register
            // Route register pada project ini biasanya berada di URL /register
            // (laravel default auth scaffolding).
            return redirect('/register')
                ->withErrors(['membership' => 'Silakan daftar terlebih dahulu.'])
                ->withInput();

        }



        $membershipStatus = Auth::user()?->membership_status;

        if ($membershipStatus !== 'active') {
            return redirect()->route('login')
                ->withErrors(['membership' => 'Akun Anda sedang menunggu persetujuan Administrator.'])
                ->withInput();
        }

        return $next($request);
    }
}


