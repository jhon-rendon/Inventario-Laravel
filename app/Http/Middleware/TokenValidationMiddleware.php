<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TokenValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /*$token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }*/


       /* $token = JWTAuth::getToken();

        if ( $token && JWTAuth::parseToken()->authenticate() ) {
            try {
                $refreshedToken = JWTAuth::refresh($token);
                JWTAuth::setToken($refreshedToken);
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer '.$refreshedToken);

                return $response;
            } catch (\Exception $e) {
                return response()->json(['error' => 'Could not refresh token'], 400);
            }
        }*/

        //return $next($request);

        try {
            // Comprobar si el token es válido
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                // Si el token es inválido
                return response()->json(['error' => 'Token inválido'], 401);
                return response()->json(
                    [ 'success' => false,
                      'message' => 'Token inválido',
                      'error'   => ['message' => 'token-invalido', 'codigo' => 10 ]
                    ], 401);
            } elseif ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                // Si el token ha expirado
                try {
                    // Intentar renovar el token
                    //$token = JWTAuth::refresh(true, true);
                   $token = JWTAuth::refresh(JWTAuth::getToken());
                   $user = JWTAuth::setToken($token)->toUser();

                 // Añadir el token renovado a la cabecera de la respuesta
                    $response = $next($request);
                    $response->headers->set('Authorization', 'Bearer ' . $token);
                    return $response;
                } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
                    // Si no se puede renovar el token
                    return response()->json(
                            [ 'success' => false,
                              'message' => 'Token expirado',
                              'error'   => ['message' => 'token-expirado', 'codigo' => 20 ]
                            ], 401);
                }
            } else {
                // Si no se puede obtener el token
                return response()->json(
                    [ 'success' => false,
                      'message' => 'Token no encontrado',
                      'error'   => ['message' => 'token-no-entrado', 'codigo' => 30 ]
                    ], 401);
            }
        }
        // Si el token es válido, continuar con la petición
        return $next($request);



        /*try{
            $user  = JWTAuth::parseToken()->autenticate();
        }
        catch( Exception $e ){

            if( $e instanceof TokenExpiredException ){
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json([
                    'success'     => false,
                    'accessToken' => $newToken,
                    'message'     => 'expired'
                ],200);
            }
            else if( $e instanceof TokenInvalidException ){
                return response()->json([
                    'success'     => false,
                    'message'     => 'token Invalid',
                ],401);
            }
            else{
                return response()->json([
                    'success'     => false,
                    'message'     => 'token Not found',
                    'error'       => $e
                ],401);
            }
        }*/

        // Verifica si el token ha expirado
        /*if (!JWTAuth::check()) {
            // Obtén el usuario actual
            $user = Auth::user();

            // Genera un nuevo token
            $token = JWTAuth::refresh();

            // Asigna el nuevo token al usuario
            JWTAuth::setToken($token)->setUser($user);
        }*/

        /*$user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }*/
        /*$token = $request->bearerToken();

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

        /*$route = Route::getRoutes()->match($request);

        if (!$route) {
            return response()->json(['message' => 'Route not found'], 404);
        }*/

        //return $next($request);
    }
}
