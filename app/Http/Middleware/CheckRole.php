<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array<string>  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $userRoles = explode(',', $user->roles); // assuming roles are stored as a comma-separated string

        // Check if any of the user's roles are in the allowed roles
        if (empty(array_intersect($userRoles, $roles))) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            } else {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }
}
