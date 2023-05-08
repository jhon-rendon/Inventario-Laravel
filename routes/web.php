<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/datos',function(){
     return DB::select('Select * from table1');
});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::fallback(function ($ruta) {
    return response()->json([
        "status" => false,
        "msg" => "La ruta  ".$ruta." No existe",
    ],404);
});
/*Route::view('/{any}','home')
    ->middleware('auth')
    ->where('any','.*');*/
