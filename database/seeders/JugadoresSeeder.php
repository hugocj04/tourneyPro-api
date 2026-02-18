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
        $usuarios = Usuario::all();
        $equipos = Equipo::all();

        if ($usuarios->count() >= 3 && $equipos->count() >= 2) {
            Jugador::create([
                'dorsal' => 10,
                'posicion' => 'Delantero',
                'idUsuario' => $usuarios[0]->idUsuario,
                'idEquipo' => $equipos[0]->idEquipo,
            ]);

            if ($usuarios->count() > 1) {
                Jugador::create([
                    'dorsal' => 7,
                    'posicion' => 'Mediocampista',
                    'idUsuario' => $usuarios[0]->idUsuario,
                    'idEquipo' => $equipos[1]->idEquipo,
                ]);
            }
        }
    }
}
