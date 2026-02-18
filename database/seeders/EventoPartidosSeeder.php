<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventoPartido;
use App\Models\Partido;
use App\Models\Equipo;

class EventoPartidosSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar el primer partido finalizado
        $partido = Partido::where('estado', 'Finalizado')->first();

        if ($partido) {
            // Gol del equipo local (sin jugador específico por ahora)
            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idJugador' => null,
                'idEquipo' => $partido->idEquipoLocal,
                'tipoEvento' => 'gol',
                'minuto' => 15,
                'descripcion' => 'Gol del equipo local',
            ]);

            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idJugador' => null,
                'idEquipo' => $partido->idEquipoLocal,
                'tipoEvento' => 'gol',
                'minuto' => 32,
                'descripcion' => 'Segundo gol del equipo local',
            ]);

            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idJugador' => null,
                'idEquipo' => $partido->idEquipoVisitante,
                'tipoEvento' => 'gol',
                'minuto' => 45,
                'descripcion' => 'Gol del equipo visitante',
            ]);

            // Tarjeta amarilla
            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idJugador' => null,
                'idEquipo' => $partido->idEquipoVisitante,
                'tipoEvento' => 'tarjeta_amarilla',
                'minuto' => 60,
                'descripcion' => 'Tarjeta amarilla por falta',
            ]);

            // Tercer gol del local
            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idJugador' => null,
                'idEquipo' => $partido->idEquipoLocal,
                'tipoEvento' => 'gol',
                'minuto' => 78,
                'descripcion' => 'Tercer gol del equipo local',
            ]);
        }
    }
}
