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
        $torneos = Torneo::orderBy('idTorneo')->get();
        $equipos = Equipo::orderBy('idEquipo')->get();

        if ($equipos->count() < 12 || $torneos->count() < 4) return;

        $rm  = $equipos[0]; $bar = $equipos[1]; $atm = $equipos[2];
        $val = $equipos[3]; $sev = $equipos[4]; $rso = $equipos[5];
        $ath = $equipos[6]; $vil = $equipos[7];
        $jAlc = $equipos[8]; $jLeg = $equipos[9];
        $jGet = $equipos[10]; $jFue = $equipos[11];

        $t1 = $torneos[0]; $t2 = $torneos[1];
        $t3 = $torneos[2]; $t4 = $torneos[3];

        // ── Torneo 1: Liga Regional Senior ──────────────────────
        $t1insc = [
            [$rm,  'aceptada',  100.00, -30],
            [$bar, 'aceptada',  100.00, -28],
            [$atm, 'aceptada',  100.00, -27],
            [$val, 'aceptada',  100.00, -25],
            [$sev, 'aceptada',  100.00, -22],
            [$rso, 'aceptada',  100.00, -20],
            [$ath, 'pendiente', null,   -5],
            [$vil, 'pendiente', null,   -3],
        ];
        foreach ($t1insc as $r) {
            InscripcionEquipo::create(['idTorneo'=>$t1->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'fechaInscripcion'=>now()->addDays($r[3]),'estado'=>$r[1],'montoAbonado'=>$r[2]]);
        }

        // ── Torneo 2: Copa de España ──────────────────────────────
        $t2insc = [
            [$rm,  'aceptada',  200.00, -45],
            [$atm, 'aceptada',  200.00, -43],
            [$ath, 'aceptada',  200.00, -40],
            [$vil, 'aceptada',  200.00, -38],
            [$bar, 'pendiente', null,   -10],
            [$val, 'pendiente', null,   -8],
            [$sev, 'rechazada', null,   -50],
        ];
        foreach ($t2insc as $r) {
            InscripcionEquipo::create(['idTorneo'=>$t2->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'fechaInscripcion'=>now()->addDays($r[3]),'estado'=>$r[1],'montoAbonado'=>$r[2]]);
        }

        // ── Torneo 3: Navidad 2025 (juveniles) ───────────────────
        foreach ([$jAlc,$jLeg,$jGet,$jFue] as $i => $eq) {
            InscripcionEquipo::create(['idTorneo'=>$t3->idTorneo,'idEquipo'=>$eq->idEquipo,
                'fechaInscripcion'=>now()->subDays(60 + $i),'estado'=>'aceptada','montoAbonado'=>50.00]);
        }

        // ── Torneo 4: Verano 2026 (pendiente) ────────────────────
        $t4insc = [
            [$rm,  'pendiente', null, -2],
            [$bar, 'pendiente', null, -1],
            [$sev, 'pendiente', null,  0],
        ];
        foreach ($t4insc as $r) {
            InscripcionEquipo::create(['idTorneo'=>$t4->idTorneo,'idEquipo'=>$r[0]->idEquipo,
                'fechaInscripcion'=>now()->addDays($r[3]),'estado'=>$r[1],'montoAbonado'=>$r[2]]);
        }
    }
}
