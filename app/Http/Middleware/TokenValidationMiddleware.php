<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class TokenValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }

        /*$user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }*/
        $token = $request->bearerToken();

        $idToken = explode('|', $token);

        if( !$idToken || !is_numeric($idToken[0])){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        else if( is_numeric($idToken[0]) && !Auth::guard('sanctum')->check() ){
            return response()->json(['message' => 'Unauthorized - Token no Valido'], 401);
        }

        //return response()->json(['message' => $validToken], 404);
        /*if (! $validToken || ! Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }*/

        $route = Route::getRoutes()->match($request);

        if (!$route) {
            return response()->json(['message' => 'Route not found'], 404);
        }

        return $next($request);
    }
}
