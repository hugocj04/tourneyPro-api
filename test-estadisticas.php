<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=" . str_repeat("=", 60) . "\n";
echo "TEST: Creación automática de estadísticas de jugadores\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// 1. Obtener un partido
$partido = App\Models\Partido::first();
echo "✓ Partido encontrado: ID={$partido->idPartido}, Torneo={$partido->idTorneo}\n";

// 2. Obtener un jugador  
$jugador = App\Models\Jugador::first();
echo "✓ Jugador encontrado: ID={$jugador->idJugador}, Nombre={$jugador->nombre}\n\n";

// 3. Crear evento de GOL
echo "📝 Creando evento de GOL...\n";
$eventoGol = App\Models\EventoPartido::create([
    'idPartido' => $partido->idPartido,
    'idJugador' => $jugador->idJugador,
    'idEquipo' => $jugador->idEquipo,
    'tipoEvento' => 'gol',
    'minuto' => 20,
]);
echo "✓ Evento creado: ID={$eventoGol->idEvento}\n\n";

// 4. Verificar estadísticas
$estadisticas = App\Models\EstadisticaJugador::where('idJugador', $jugador->idJugador)
    ->where('idTorneo', $partido->idTorneo)
    ->first();

if ($estadisticas) {
    echo "✅ ÉXITO: Estadísticas creadas/actualizadas automáticamente\n";
    echo "   - Goles: {$estadisticas->goles}\n";
    echo "   - Asistencias: {$estadisticas->asistencias}\n";
    echo "   - Tarjetas Amarillas: {$estadisticas->tarjetasAmarillas}\n";
    echo "   - Tarjetas Rojas: {$estadisticas->tarjetasRojas}\n\n";
} else {
    echo "❌ ERROR: No se crearon estadísticas\n\n";
}

// 5. Crear evento de TARJETA AMARILLA
echo "📝 Creando evento de TARJETA AMARILLA...\n";
$eventoTarjeta = App\Models\EventoPartido::create([
    'idPartido' => $partido->idPartido,
    'idJugador' => $jugador->idJugador,
    'idEquipo' => $jugador->idEquipo,
    'tipoEvento' => 'tarjeta_amarilla',
    'minuto' => 45,
]);
echo "✓ Evento creado: ID={$eventoTarjeta->idEvento}\n\n";

// 6. Verificar estadísticas actualizadas
$estadisticas->refresh();
echo "📊 Estadísticas actualizadas:\n";
echo "   - Goles: {$estadisticas->goles}\n";
echo "   - Tarjetas Amarillas: {$estadisticas->tarjetasAmarillas}\n";
echo "   - Tarjetas Rojas: {$estadisticas->tarjetasRojas}\n\n";

// 7. Eliminar evento y verificar reversión
echo "🗑️  Eliminando evento de tarjeta...\n";
$eventoTarjeta->delete();
$estadisticas->refresh();
echo "✓ Evento eliminado\n\n";

echo "📊 Estadísticas después de eliminar:\n";
echo "   - Goles: {$estadisticas->goles}\n";
echo "   - Tarjetas Amarillas: {$estadisticas->tarjetasAmarillas}\n";
echo "   - Tarjetas Rojas: {$estadisticas->tarjetasRojas}\n\n";

echo "=" . str_repeat("=", 60) . "\n";
echo "✅ TEST COMPLETADO\n";
echo "=" . str_repeat("=", 60) . "\n";
