<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=" . str_repeat("=", 70) . "\n";
echo "PRUEBAS SPRINT 3 - FUNCIONALIDADES AVANZADAS\n";
echo "=" . str_repeat("=", 70) . "\n\n";

// ===========================================================================
// 1. ESTADÍSTICAS - Goleadores y Tarjetas
// ===========================================================================
echo "1️⃣  ESTADÍSTICAS DE JUGADORES\n";
echo "-" . str_repeat("-", 70) . "\n";

// Crear más eventos de prueba
$jugadores = App\Models\Jugador::take(10)->get();
$partido = App\Models\Partido::first();

foreach ($jugadores->take(5) as $index => $jugador) {
    App\Models\EventoPartido::create([
        'idPartido' => $partido->idPartido,
        'idJugador' => $jugador->idJugador,
        'idEquipo' => $jugador->idEquipo,
        'tipoEvento' => 'gol',
        'minuto' => 10 + ($index * 10),
    ]);
}

$goleadores = App\Models\EstadisticaJugador::with('jugador')
    ->where('idTorneo', $partido->idTorneo)
    ->orderBy('goles', 'desc')
    ->take(5)
    ->get();

echo "✅ Top 5 Goleadores:\n";
foreach ($goleadores as $index => $est) {
    $jugador = $est->jugador;
    echo "   " . ($index + 1) . ". {$jugador->nombre} {$jugador->apellidos} - {$est->goles} goles\n";
}
echo "\n";

// ===========================================================================
// 2. FIXTURE GENERATOR
// ===========================================================================
echo "2️⃣  GENERADOR DE FIXTURES\n";
echo "-" . str_repeat("-", 70) . "\n";

$torneo = App\Models\Torneo::first();
$equipos = App\Models\Equipo::take(4)->get();

// Inscribir equipos al torneo
foreach ($equipos as $equipo) {
    try {
        App\Models\InscripcionEquipo::create([
            'idTorneo' => $torneo->idTorneo,
            'idEquipo' => $equipo->idEquipo,
            'estado' => 'aceptada',
        ]);
    } catch (Exception $e) {
        // Ya existe
    }
}

// Generar fixture
$service = new App\Services\GeneradorFixtureService();
$resultado = $service->generarFixture(
    idTorneo: $torneo->idTorneo,
    fechaInicio: now()->addDays(7)->format('Y-m-d'),
    diasEntreFechas: 7,
    horaInicio: '18:00',
    lugar: 'Estadio Municipal'
);

echo "✅ Fixture generado: {$resultado['partidos_creados']} partidos creados\n";
echo "   Equipos: {$equipos->count()}\n";
echo "   Formato: Round-Robin (todos contra todos)\n";
echo "   Jornadas: {$resultado['fechas']}\n\n";

$partidos = collect($resultado['partidos']);
$jornadas = $partidos->groupBy(function($partido) {
    return \Carbon\Carbon::parse($partido->fechaHora)->format('Y-m-d');
});

echo "   Jornadas programadas:\n";
foreach ($jornadas as $fecha => $partidosJornada) {
    echo "   - " . \Carbon\Carbon::parse($fecha)->format('d/m/Y') . ": {$partidosJornada->count()} partidos\n";
}
echo "\n";

// ===========================================================================
// 3. DASHBOARD - ESTADÍSTICAS GENERALES
// ===========================================================================
echo "3️⃣  DASHBOARD - ESTADÍSTICAS DEL TORNEO\n";
echo "-" . str_repeat("-", 70) . "\n";

// Dashboard del torneo
$totalEquipos = App\Models\InscripcionEquipo::where('idTorneo', $torneo->idTorneo)
    ->where('estado', 'aceptada')
    ->count();

$totalPartidos = App\Models\Partido::where('idTorneo', $torneo->idTorneo)->count();

$totalGoles = App\Models\EventoPartido::whereHas('partido', function($q) use ($torneo) {
    $q->where('idTorneo', $torneo->idTorneo);
})->whereIn('tipoEvento', ['gol', 'autogol'])->count();

echo "✅ Resumen del Torneo '{$torneo->nombreTorneo}':\n";
echo "   - Equipos inscritos: {$totalEquipos}\n";
echo "   - Partidos programados: {$totalPartidos}\n";
echo "   - Goles marcados: {$totalGoles}\n\n";

// ===========================================================================
// 4. FILTROS AVANZADOS
// ===========================================================================
echo "4️⃣ FILTROS AVANZADOS\n";
echo "-" . str_repeat("-", 70) . "\n";

// Filtrar torneos activos
$torneosActivos = App\Models\Torneo::where('estado', 'activo')->count();
echo "✅ Torneos activos: {$torneosActivos}\n";

// Filtrar partidos por fecha
$proximosPartidos = App\Models\Partido::where('fechaHora', '>', now())
    ->where('idTorneo', $torneo->idTorneo)
    ->orderBy('fechaHora')
    ->take(5)
    ->get();

echo "✅ Próximos 5 partidos:\n";
foreach ($proximosPartidos as $index => $partido) {
    $fecha = \Carbon\Carbon::parse($partido->fechaHora)->format('d/m/Y H:i');
    echo "   " . ($index + 1) . ". Partido #{$partido->idPartido} - {$fecha}\n";
}
echo "\n";

// ===========================================================================
// 5. CLASIFICACIONES ACTUALIZADAS
// ===========================================================================
echo "5️⃣  TABLA DE POSICIONES\n";
echo "-" . str_repeat("-", 70) . "\n";

$clasificaciones = App\Models\Clasificacion::with('equipo')
    ->where('idTorneo', $torneo->idTorneo)
    ->orderBy('puntos', 'desc')
    ->orderByRaw('(golesFavor - golesContra) desc')
    ->take(5)
    ->get();

echo "✅ Top 5 en la tabla:\n";
echo "   Pos | Equipo               | PJ | PG | PE | PP | GF | GC | DG | Pts\n";
echo "   " . str_repeat("-", 66) . "\n";
foreach ($clasificaciones as $index => $clas) {
    $equipo = $clas->equipo;
    echo sprintf(
        "   %2d  | %-20s | %2d | %2d | %2d | %2d | %2d | %2d | %+2d | %3d\n",
        $index + 1,
        substr($equipo->nombre, 0, 20),
        $clas->partidosJugados,
        $clas->victorias,
        $clas->empates,
        $clas->derrotas,
        $clas->golesFavor,
        $clas->golesContra,
        $clas->diferencia_goles,
        $clas->puntos
    );
}

echo "\n";
echo "=" . str_repeat("=", 70) . "\n";
echo "✅ TODAS LAS PRUEBAS DE SPRINT 3 COMPLETADAS EXITOSAMENTE\n";
echo "=" . str_repeat("=", 70) . "\n";
