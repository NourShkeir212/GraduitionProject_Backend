<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// User token validation middleware
class UserAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

