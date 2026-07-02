<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetRoleRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware placeholder untuk roadmap redirect berdasarkan role.
        // Tidak mengubah response saat ini.
        return $next($request);
    }
}

