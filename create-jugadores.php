<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Creando jugadores de prueba...\n\n";

$usuario = App\Models\Usuario::first();
if (!$usuario) {
    die("No hay usuarios en la base de datos\n");
}

$equipos = App\Models\Equipo::all();

$posiciones = ['Portero', 'Defensa', 'Mediocampista', 'Delantero'];
$nombres = ['Juan', 'Pedro', 'Luis', 'Carlos', 'Miguel','María', 'Ana', 'Sofia', 'Elena', 'Laura'];
$apellidos = ['García', 'Martínez', 'López', 'Fernández', 'González', 'Rodríguez', 'Pérez', 'Sánchez'];

foreach ($equipos as $equipo) {
    for ($i = 1; $i <= 11; $i++) {
        try {
            App\Models\Jugador::create([
                'nombre' => $nombres[array_rand($nombres)],
                'apellidos' => $apellidos[array_rand($apellidos)],
                'dorsal' => $i,
                'posicion' => $posiciones[array_rand($posiciones)],
                'idUsuario' => $usuario->idUsuario,
                'idEquipo' => $equipo->idEquipo,
            ]);
            echo "✓ Jugador #{$i} creado para equipo {$equipo->nombre}\n";
        } catch (Exception $e) {
            echo "✗ Error: {$e->getMessage()}\n";
        }
    }
}

echo "\nTotal de jugadores: " . App\Models\Jugador::count() . "\n";
