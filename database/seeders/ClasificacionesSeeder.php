<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clasificacion;
use App\Models\Equipo;
use App\Models\Torneo;

class ClasificacionesSeeder extends Seeder
{
    public function run(): void
    {
        $torneos = Torneo::orderBy('idTorneo')->get();
        $equipos = Equipo::orderBy('idEquipo')->get();

        if ($equipos->count() < 12 || $torneos->count() < 3) return;

        $rm  = $equipos[0]; $bar = $equipos[1]; $atm = $equipos[2];
        $val = $equipos[3]; $sev = $equipos[4]; $rso = $equipos[5];
        $ath = $equipos[6]; $vil = $equipos[7];
        $jAlc = $equipos[8]; $jLeg = $equipos[9];
        $jGet = $equipos[10]; $jFue = $equipos[11];

        $t1 = $torneos[0]; $t2 = $torneos[1]; $t3 = $torneos[2];

        // ── Torneo 1: Liga Regional Senior ──────────────────────
        // Resultados calculados de las 6 jornadas finalizadas
        $t1rows = [
            // [equipo, pts, pj, V, E, D, GF, GC]
            [$rm,  6, 2, 2, 0, 0, 4, 2],
            [$atm, 4, 2, 1, 1, 0, 3, 1],
            [$sev, 4, 2, 1, 1, 0, 4, 1],
            [$val, 1, 2, 0, 1, 1, 2, 3],
            [$rso, 1, 2, 0, 1, 1, 1, 4],
            [$bar, 0, 2, 0, 0, 2, 1, 4],
        ];
        foreach ($t1rows as $r) {
            Clasificacion::create(['idTorneo'=>$t1->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'puntos'=>$r[1],'partidosJugados'=>$r[2],'victorias'=>$r[3],
                'empates'=>$r[4],'derrotas'=>$r[5],'golesFavor'=>$r[6],'golesContra'=>$r[7]]);
        }

        // ── Torneo 2: Copa de España ──────────────────────────────
        $t2rows = [
            [$rm,  3, 1, 1, 0, 0, 3, 0],
            [$atm, 3, 1, 1, 0, 0, 1, 0],
            [$ath, 0, 1, 0, 0, 1, 0, 3],
            [$vil, 0, 1, 0, 0, 1, 0, 1],
        ];
        foreach ($t2rows as $r) {
            Clasificacion::create(['idTorneo'=>$t2->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'puntos'=>$r[1],'partidosJugados'=>$r[2],'victorias'=>$r[3],
                'empates'=>$r[4],'derrotas'=>$r[5],'golesFavor'=>$r[6],'golesContra'=>$r[7]]);
        }

        // ── Torneo 3: Navidad 2025 (finalizado) ──────────────────
        $t3rows = [
            [$jAlc, 7, 3, 2, 1, 0, 6, 3],
            [$jGet, 4, 3, 1, 1, 1, 3, 4],
            [$jFue, 3, 3, 1, 0, 2, 6, 6],
            [$jLeg, 3, 3, 1, 0, 2, 2, 4],
        ];
        foreach ($t3rows as $r) {
            Clasificacion::create(['idTorneo'=>$t3->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'puntos'=>$r[1],'partidosJugados'=>$r[2],'victorias'=>$r[3],
                'empates'=>$r[4],'derrotas'=>$r[5],'golesFavor'=>$r[6],'golesContra'=>$r[7]]);
        }
    }
}
