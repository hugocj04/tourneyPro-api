<?php
require __DIR__ . '/auth.php';

use App\Http\Controllers\SecuenciaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('secuencias', SecuenciaController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('administradores', AdministradorController::class);
    Route::apiResource('equipos', EquipoController::class);
    Route::apiResource('torneos', TorneoController::class);
    Route::apiResource('jugadores', JugadorController::class);
    Route::apiResource('clasificaciones', ClasificacionController::class);
    Route::apiResource('partidos', PartidoController::class);
    Route::apiResource('notificaciones', NotificacionController::class);
});
