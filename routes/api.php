<?php

use App\Http\Controllers\EntradaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// rutas para acceder a los métodos
Route::get('/entradas', [EntradaController::class, 'index']);
Route::get('/entradas/{entrada}', [EntradaController::class, 'show']);
Route::post('/entradas/find', [EntradaController::class, 'find']);
Route::post('/entradas', [EntradaController::class, 'store']);
Route::put('/entradas/{entrada}', [EntradaController::class, 'update']);