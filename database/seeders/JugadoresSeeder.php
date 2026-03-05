<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Usuario;
use App\Models\Equipo;

class JugadoresSeeder extends Seeder
{
    public function run(): void
    {
        $jugadorUsers = Usuario::where('rol', 'jugador')->orderBy('idUsuario')->get();
        $equipos      = Equipo::orderBy('idEquipo')->get();

        if ($jugadorUsers->isEmpty() || $equipos->isEmpty()) return;

        $posiciones = ['Portero', 'Defensa', 'Defensa', 'Mediocampista', 'Mediocampista', 'Delantero'];

        foreach ($equipos as $idx => $equipo) {
            if (!isset($jugadorUsers[$idx])) break;
            Jugador::create([
                'dorsal'    => ($idx % 11) + 1,
                'posicion'  => $posiciones[$idx % count($posiciones)],
                'idUsuario' => $jugadorUsers[$idx]->idUsuario,
                'idEquipo'  => $equipo->idEquipo,
            ]);
        }
    }
}
