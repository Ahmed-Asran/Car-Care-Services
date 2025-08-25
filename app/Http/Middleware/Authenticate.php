<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) { // Or your auth check
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login'); // Only for web
        }

        return $next($request);
    }
    protected function redirectTo($request)
{
    // For API requests, don’t redirect, just return null
    if ($request->expectsJson()) {
        return null;
    }

    // For web requests, redirect to login if needed
    return route('login');
}
}
