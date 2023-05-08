<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateJsonHeaders
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Content-Type') !== 'application/json') {
            return response()->json([
                'message' => 'Invalid Content-Type header',
            ], 415);
        }

        if (!$request->wantsJson()) {
            return response()->json([
                'message' => 'Invalid Accept header',
            ], 406);
        }

        return $next($request);
    }
}
