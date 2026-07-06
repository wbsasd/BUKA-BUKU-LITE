<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogLoginAttemptMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log minimal untuk audit sementara
        try {
            $email = $request->input('email');
            Log::info('[LOGIN] attempt', [
                'email' => $email,
                'path' => $request->path(),
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        $response = $next($request);

        if (Auth::check()) {
            Log::info('[LOGIN] success', [
                'email' => Auth::user()?->email,
                'role' => Auth::user()?->role,
                'membership_status' => Auth::user()?->membership_status,
            ]);
        } else {
            Log::info('[LOGIN] not-authenticated', [
                'path' => $request->path(),
            ]);
        }

        return $response;
    }
}

