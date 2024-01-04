<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


// Worker token validation middleware
class WorkerAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->worker()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
