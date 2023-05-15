<?php

use App\Http\Controllers\Api\CategoriaArticuloController;
use App\Http\Controllers\Api\EstadoArticuloController;
use App\Http\Controllers\Api\KardexArticuloController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\SubcategoriaArticuloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TipoUbicacionController;
use App\Http\Controllers\Api\UbicacionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});*/


Route::post('/auth/login', [UserController::class, 'login'],['only' =>['login']]);
Route::post('/auth/register', [UserController::class, 'register']);


//Route::group( ['middleware' => ["token.validation",]], function(){


    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('/auth/logout', [UserController::class, 'logout']);
    Route::get('/auth/refresh', [UserController::class, 'refresh']);

    //Route::prefix("categoria-articulos")->group( function(){

    Route::apiResource("/categoria-articulos",CategoriaArticuloController::class,['only'=>['index','update','store','show']]);
   // Route::get("/categoria-articulos/listarAll",[CategoriaArticuloController::class,"listarCategoriasAll"]);


    Route::apiResource("/subcategoria-articulos",SubcategoriaArticuloController::class);
    Route::apiResource("/marcas",MarcaController::class);
    Route::apiResource("/tipo-ubicacion",TipoUbicacionController::class);
    Route::apiResource("/ubicacion",UbicacionController::class);
    Route::apiResource("/estado-articulo",EstadoArticuloController::class);
    Route::apiResource("/kardex-articulos",KardexArticuloController::class);


//});



Route::fallback(function ($ruta) {
    return response()->json([
        "status" => false,
        "msg" => "La ruta  ".$ruta." No existe",
    ],404);
});

/*Route::any('/{any}', function ($any) {
    //abort(404, "La ruta '$any' no existe.");
    return response()->json([
        "status" => false,
        "msg" => "La ruta  ".$any." No existe",
    ],404);
})->where('any', '.*');*/


