<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return response()->json([
                "message" => "No Autenticado",
                "error" => "Token no valido"
             ],403);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ( $request->user()->can($permission) ) {
                return $next($request);
            }
        }

        return response()->json([
                "message" => "No autorizado",
                "error" => "No cuenta con el rol o permiso autorizado"
             ],403);
     }
}
