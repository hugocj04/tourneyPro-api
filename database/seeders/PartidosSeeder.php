<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Torneo;

class PartidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipos = Equipo::all();
        $torneo = Torneo::first();

        if ($equipos->count() >= 2 && $torneo) {
            Partido::create([
                'fecha' => '2026-03-15',
                'hora' => '18:00:00',
                'lugar' => 'Estadio Santiago Bernabéu',
                'resultadoLocal' => 3,
                'resultadoVisitante' => 1,
                'estado' => 'Finalizado',
                'idTorneo' => $torneo->idTorneo,
                'idEquipoLocal' => $equipos[0]->IdEquipo,
                'idEquipoVisitante' => $equipos[1]->IdEquipo,
            ]);

            Partido::create([
                'fecha' => '2026-03-22',
                'hora' => '20:00:00',
                'lugar' => 'Camp Nou',
                'resultadoLocal' => null,
                'resultadoVisitante' => null,
                'estado' => 'Próximo',
                'idTorneo' => $torneo->idTorneo,
                'idEquipoLocal' => $equipos[1]->IdEquipo,
                'idEquipoVisitante' => $equipos[0]->IdEquipo,
            ]);
        }
    }
}
