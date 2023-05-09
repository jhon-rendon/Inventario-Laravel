<?php

use App\Http\Controllers\Api\CategoriaArticuloController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\SubcategoriaArticuloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TipoUbicacionController;

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


Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);
/*Route::get('datos',function(){
    return DB::select('Select * from table1');
});*/


Route::group( ['middleware' => ["auth:api",]], function(){


    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('/auth/logout', [UserController::class, 'logout']);
    Route::get('/auth/refresh', [UserController::class, 'refresh']);

    //Route::prefix("categoria-articulos")->group( function(){

    Route::resource("/categoria-articulos",CategoriaArticuloController::class, ['only' => ['index', 'show', 'update','store'] ]);
    Route::resource("/subcategoria-articulos",SubcategoriaArticuloController::class,  ['only' => ['index', 'show', 'update','store'] ]);
    Route::resource("/marcas",MarcaController::class,  ['only' => ['index', 'show', 'update','store'] ]);
    Route::resource("/tipo-ubicacion",TipoUbicacionController::class,  ['only' => ['index', 'show', 'update','store'] ]);

});



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


