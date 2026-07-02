<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Contoh penggunaan di route:
     *   ->middleware(['auth', 'role:admin'])
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403);
        }

        $userRole = Auth::user()?->role;

        if (!in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}

