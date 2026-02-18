<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InscripcionEquipo;
use App\Models\Equipo;
use App\Models\Torneo;

class InscripcionEquiposSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = Equipo::all();
        $torneos = Torneo::all();

        if ($equipos->count() >= 2 && $torneos->count() >= 1) {
            // Inscribir equipos en el primer torneo
            InscripcionEquipo::create([
                'idTorneo' => $torneos[0]->idTorneo,
                'idEquipo' => $equipos[0]->idEquipo,
                'fechaInscripcion' => now()->subDays(10),
                'estado' => 'aceptada',
                'montoAbonado' => $torneos[0]->precioInscripcion,
            ]);

            InscripcionEquipo::create([
                'idTorneo' => $torneos[0]->idTorneo,
                'idEquipo' => $equipos[1]->idEquipo,
                'fechaInscripcion' => now()->subDays(8),
                'estado' => 'aceptada',
                'montoAbonado' => $torneos[0]->precioInscripcion,
            ]);

            // Inscripción pendiente
            if ($equipos->count() >= 3) {
                InscripcionEquipo::create([
                    'idTorneo' => $torneos[0]->idTorneo,
                    'idEquipo' => $equipos[2]->idEquipo,
                    'fechaInscripcion' => now()->subDays(2),
                    'estado' => 'pendiente',
                    'montoAbonado' => null,
                ]);
            }
        }
    }
}
