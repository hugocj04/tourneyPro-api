<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Usuario;
use App\Models\Equipo;

class JugadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = Usuario::all();
        $equipos = Equipo::all();

        if ($usuarios->count() >= 3 && $equipos->count() >= 2) {
            Jugador::create([
                'dorsal' => 10,
                'posicion' => 'Delantero',
                'idUsuario' => $usuarios[2]->idUsuario,
                'IdEquipo' => $equipos[0]->IdEquipo,
            ]);

            Jugador::create([
                'dorsal' => 7,
                'posicion' => 'Mediocampista',
                'idUsuario' => $usuarios[3]->idUsuario,
                'IdEquipo' => $equipos[0]->IdEquipo,
            ]);

            Jugador::create([
                'dorsal' => 9,
                'posicion' => 'Delantero',
                'idUsuario' => $usuarios[4]->idUsuario,
                'IdEquipo' => $equipos[1]->IdEquipo,
            ]);
        }
    }
}
