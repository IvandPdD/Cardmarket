<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CartaController;
use App\Http\Controllers\ColeccionController;
use App\Http\Controllers\VentaController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('usuarios')->group(function () {
	Route::post('/register',[UsuarioController::class,"registrar"]);
	Route::post('/login',[UsuarioController::class,"logear"]);
	Route::post('/restablecer-pass',[UsuarioController::class,"restablecerPass"]);
});

Route::prefix('cartas')->group(function () {
	Route::post('/alta',[CartaController::class,"alta"])->middleware('admin');
	Route::post('/buscar',[CartaController::class,"buscar"])/*->middleware('admin')*/;
	Route::post('/venta',[VentaController::class,"venta"])->middleware('venta');
});

Route::prefix('colecciones')->group(function () {
	Route::post('/alta',[ColeccionController::class,"alta"])->middleware('admin');
});
