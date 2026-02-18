<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Torneo;

class PartidosSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = Equipo::all();
        $torneo = Torneo::first();

        if ($equipos->count() >= 2 && $torneo) {
            Partido::create([
                'fechaHora' => '2026-03-15 18:00:00',
                'lugar' => 'Estadio Santiago Bernabeu',
                'resultadoLocal' => 3,
                'resultadoVisitante' => 1,
                'estado' => 'Finalizado',
                'idTorneo' => $torneo->idTorneo,
                'idEquipoLocal' => $equipos[0]->idEquipo,
                'idEquipoVisitante' => $equipos[1]->idEquipo,
            ]);

            Partido::create([
                'fechaHora' => '2026-03-22 20:00:00',
                'lugar' => 'Camp Nou',
                'resultadoLocal' => null,
                'resultadoVisitante' => null,
                'estado' => 'Proximo',
                'idTorneo' => $torneo->idTorneo,
                'idEquipoLocal' => $equipos[1]->idEquipo,
                'idEquipoVisitante' => $equipos[0]->idEquipo,
            ]);
        }
    }
}
