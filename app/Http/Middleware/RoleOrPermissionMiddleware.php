<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roleOrPermission, $guard = null)
    {
        $authGuard = Auth::guard($guard);
        if ($authGuard->guest()) {
            return response()->json([
                "message" => "No Autenticado",
                "error" => "Token no valido"
             ],403);
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! $authGuard->user()->hasAnyRole($rolesOrPermissions) && ! $authGuard->user()->hasAnyPermission($rolesOrPermissions)) {

            $permisos = $authGuard->user()->getPermissionsViaRoles()->pluck('name');

            if( is_array( $roleOrPermission ) ){

                foreach( $permisos as $per ){

                    foreach( $roleOrPermission as $rolPerm){
                        if(  strtolower( $rolPerm ) === strtolower( $per) ){
                            return $next($request);
                        }
                    }
                }
            }else{

                foreach( $permisos as $per ){
                    if(  strtolower( $roleOrPermission) === strtolower( $per) ){
                        return $next($request);
                    }
                }

            }

            return response()->json([
                "message" => "No autorizado",
                "error" => "No cuenta con el rol o permiso autorizado",
                "permsisos" => $permisos,
             ],403);
        }

        return $next($request);
    }
}
