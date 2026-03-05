<?php
require __DIR__ . '/auth.php';

use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\EstadisticaJugadorController;
use App\Http\Controllers\EventoPartidoController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\InscripcionEquipoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas protegidas - requieren autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('equipos', EquipoController::class);
    Route::apiResource('torneos', TorneoController::class);
    Route::apiResource('jugadores', JugadorController::class)
        ->parameters(['jugadores' => 'jugador']);
    Route::apiResource('clasificaciones', ClasificacionController::class)
        ->parameters(['clasificaciones' => 'clasificacion']);
    Route::apiResource('partidos', PartidoController::class);
    Route::get('partidos/{partido}/eventos',  [EventoPartidoController::class, 'indexByPartido']);
    Route::post('partidos/{partido}/eventos', [EventoPartidoController::class, 'storeByPartido']);
    Route::apiResource('inscripciones', InscripcionEquipoController::class)
        ->parameters(['inscripciones' => 'inscripcionEquipo']);
    Route::apiResource('eventos', EventoPartidoController::class)
        ->parameters(['eventos' => 'eventoPartido']);
    
    // Rutas de estadísticas
    Route::get('estadisticas', [EstadisticaJugadorController::class, 'index']);
    Route::get('estadisticas/{estadisticaJugador}', [EstadisticaJugadorController::class, 'show']);
    Route::get('estadisticas/goleadores/ranking', [EstadisticaJugadorController::class, 'goleadores']);
    
    // Rutas de fixture
    Route::post('fixture/generar', [FixtureController::class, 'generar']);
    Route::post('fixture/limpiar', [FixtureController::class, 'limpiar']);
    
    // Rutas de dashboard
    Route::get('dashboard/resumen', [DashboardController::class, 'resumenGeneral']);
    Route::get('dashboard/resumen-movil', [DashboardController::class, 'resumenMovil']);
    Route::get('dashboard/torneo/{idTorneo}', [DashboardController::class, 'torneo']);
    Route::get('dashboard/torneo/{idTorneo}/tabla', [DashboardController::class, 'tablaPosiciones']);
    Route::get('dashboard/torneo/{idTorneo}/goleadores', [DashboardController::class, 'goleadores']);
    Route::get('dashboard/torneo/{idTorneo}/mejor-ataque', [DashboardController::class, 'mejorAtaque']);
    Route::get('dashboard/torneo/{idTorneo}/mejor-defensa', [DashboardController::class, 'mejorDefensa']);
    Route::get('dashboard/torneo/{idTorneo}/proximos-partidos', [DashboardController::class, 'proximosPartidos']);
    Route::get('dashboard/torneo/{idTorneo}/ultimos-resultados', [DashboardController::class, 'ultimosResultados']);
    Route::get('dashboard/torneo/{idTorneo}/equipo/{idEquipo}', [DashboardController::class, 'estadisticasEquipo']);
});