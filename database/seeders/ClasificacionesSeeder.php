<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clasificacion;
use App\Models\Equipo;
use App\Models\Torneo;

class ClasificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipos = Equipo::all();
        $torneo = Torneo::first();

        if ($equipos->count() > 0 && $torneo) {
            Clasificacion::create([
                'puntos' => 9,
                'partidosJugados' => 3,
                'victorias' => 3,
                'empates' => 0,
                'derrotas' => 0,
                'golesFavor' => 8,
                'golesContra' => 2,
                'idEquipo' => $equipos[0]->IdEquipo,
                'idTorneo' => $torneo->idTorneo,
            ]);

            Clasificacion::create([
                'puntos' => 6,
                'partidosJugados' => 3,
                'victorias' => 2,
                'empates' => 0,
                'derrotas' => 1,
                'golesFavor' => 5,
                'golesContra' => 3,
                'idEquipo' => $equipos[1]->IdEquipo,
                'idTorneo' => $torneo->idTorneo,
            ]);
        }
    }
}
