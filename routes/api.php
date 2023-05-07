<?php

use App\Http\Controllers\Api\CategoriaArticuloController;
use App\Http\Controllers\Api\MarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


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

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});


Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::get('datos',function(){
    return DB::select('Select * from table1');
});


Route::group( ['middleware' => ["auth:sanctum"]], function(){
    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('/auth/logout', [UserController::class, 'logout']);




    Route::prefix("articulos")->group( function(){

       Route::middleware(['role_or_permission:articulo_show'])->get('/',[CategoriaArticuloController::class,"index"]);


        //Route::get("/",[CategoriaArticuloController::class,"index"])->middleware(['permission:prueba']);
        Route::get("/{id}",[CategoriaArticuloController::class,"show"])->where(['id' => '[0-9]+']);
        Route::put("/{id}",[CategoriaArticuloController::class,"update"])->where(['id' => '[0-9]+']);
        Route::post("/",[CategoriaArticuloController::class,"store"]);

    });

    Route::prefix("marcas")->group( function(){
        Route::get("/",[MarcaController::class,"index"]);
        Route::get("/{id}",[MarcaController::class,"show"])->where(['id' => '[0-9]+']);
        Route::put("/{id}",[MarcaController::class,"update"])->where(['id' => '[0-9]+']);
        Route::post("/",[MarcaController::class,"store"]);

    });
});




Route::any('/{any}', function ($any) {
    //abort(404, "La ruta '$any' no existe.");
    return response()->json([
        "status" => false,
        "msg" => "La ruta  ".$any." No existe",
    ],404);
})->where('any', '.*');


