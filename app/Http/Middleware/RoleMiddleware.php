<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role , $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            return response()->json([
                "message" => "No Autenticado",
                "error" => "Token no valido"
             ],403);
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! $authGuard->user()->hasAnyRole($roles)) {
            return response()->json([
                "message" => "No autorizado",
                "error" => "No cuenta con el rol o permiso autorizado"
             ],403);
        }

        return $next($request);
    }
}
