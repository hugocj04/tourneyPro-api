<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Torneo;

class PartidosSeeder extends Seeder
{
    public function run(): void
    {
        $torneos = Torneo::orderBy('idTorneo')->get();
        $equipos = Equipo::orderBy('idEquipo')->get();

        if ($equipos->count() < 8 || $torneos->count() < 3) return;

        $rm  = $equipos[0]; 
        $bar = $equipos[1]; 
        $atm = $equipos[2]; 
        $val = $equipos[3]; 
        $sev = $equipos[4]; 
        $rso = $equipos[5]; 
        $ath = $equipos[6];
        $vil = $equipos[7];

        $jAlc = $equipos[8];
        $jLeg = $equipos[9];
        $jGet = $equipos[10];
        $jFue = $equipos[11];

        $t1 = $torneos[0]; 
        $t2 = $torneos[1]; 
        $t3 = $torneos[2]; 

        $partidoT1 = [
            ['local'=>$rm, 'visitante'=>$bar, 'fecha'=>'2026-01-18 17:00:00', 'rl'=>2, 'rv'=>1, 'estado'=>'finalizado', 'lugar'=>'Santiago Bernabéu'],
            ['local'=>$atm, 'visitante'=>$val, 'fecha'=>'2026-01-18 19:00:00', 'rl'=>1, 'rv'=>1, 'estado'=>'finalizado', 'lugar'=>'Metropolitano'],
            ['local'=>$sev, 'visitante'=>$rso, 'fecha'=>'2026-01-25 17:00:00', 'rl'=>3, 'rv'=>0, 'estado'=>'finalizado', 'lugar'=>'Ramón Sánchez Pizjuán'],
            ['local'=>$bar, 'visitante'=>$atm, 'fecha'=>'2026-02-01 17:00:00', 'rl'=>0, 'rv'=>2, 'estado'=>'finalizado', 'lugar'=>'Camp Nou'],
            ['local'=>$val, 'visitante'=>$rm,  'fecha'=>'2026-02-01 19:00:00', 'rl'=>1, 'rv'=>2, 'estado'=>'finalizado', 'lugar'=>'Mestalla'],
            ['local'=>$rso, 'visitante'=>$sev, 'fecha'=>'2026-02-08 17:00:00', 'rl'=>1, 'rv'=>1, 'estado'=>'finalizado', 'lugar'=>'Anoeta'],
            ['local'=>$rm,  'visitante'=>$atm, 'fecha'=>'2026-03-08 17:00:00', 'rl'=>null, 'rv'=>null, 'estado'=>'programado', 'lugar'=>'Santiago Bernabéu'],
            ['local'=>$bar, 'visitante'=>$val, 'fecha'=>'2026-03-08 19:00:00', 'rl'=>null, 'rv'=>null, 'estado'=>'programado', 'lugar'=>'Camp Nou'],
            ['local'=>$sev, 'visitante'=>$rm,  'fecha'=>today()->toDateString().' 20:00:00', 'rl'=>null, 'rv'=>null, 'estado'=>'programado', 'lugar'=>'Ramón Sánchez Pizjuán'],
        ];

        foreach ($partidoT1 as $p) {
            Partido::create([
                'fechaHora'         => $p['fecha'],
                'lugar'             => $p['lugar'],
                'resultadoLocal'    => $p['rl'],
                'resultadoVisitante'=> $p['rv'],
                'estado'            => $p['estado'],
                'idTorneo'          => $t1->idTorneo,
                'idEquipoLocal'     => $p['local']->idEquipo,
                'idEquipoVisitante' => $p['visitante']->idEquipo,
            ]);
        }

        $partidoT2 = [
            ['local'=>$rm,  'visitante'=>$ath, 'fecha'=>'2026-02-15 17:00:00', 'rl'=>3, 'rv'=>0, 'estado'=>'finalizado', 'lugar'=>'Santiago Bernabéu'],
            ['local'=>$atm, 'visitante'=>$vil, 'fecha'=>'2026-02-15 19:00:00', 'rl'=>1, 'rv'=>0, 'estado'=>'finalizado', 'lugar'=>'Metropolitano'],
            ['local'=>$rm,  'visitante'=>$atm, 'fecha'=>'2026-03-22 19:00:00', 'rl'=>null, 'rv'=>null, 'estado'=>'programado', 'lugar'=>'Santiago Bernabéu'],
        ];

        foreach ($partidoT2 as $p) {
            Partido::create([
                'fechaHora'         => $p['fecha'],
                'lugar'             => $p['lugar'],
                'resultadoLocal'    => $p['rl'],
                'resultadoVisitante'=> $p['rv'],
                'estado'            => $p['estado'],
                'idTorneo'          => $t2->idTorneo,
                'idEquipoLocal'     => $p['local']->idEquipo,
                'idEquipoVisitante' => $p['visitante']->idEquipo,
            ]);
        }

        $partidoT3 = [
            ['local'=>$jAlc, 'visitante'=>$jLeg, 'fecha'=>'2025-12-21 10:00:00', 'rl'=>2, 'rv'=>0, 'lugar'=>'Campo Municipal Alcobendas'],
            ['local'=>$jGet, 'visitante'=>$jFue, 'fecha'=>'2025-12-21 12:00:00', 'rl'=>1, 'rv'=>3, 'lugar'=>'Campo Municipal Getafe'],
            ['local'=>$jAlc, 'visitante'=>$jGet, 'fecha'=>'2025-12-27 10:00:00', 'rl'=>1, 'rv'=>1, 'lugar'=>'Campo Municipal Alcobendas'],
            ['local'=>$jLeg, 'visitante'=>$jFue, 'fecha'=>'2025-12-27 12:00:00', 'rl'=>2, 'rv'=>1, 'lugar'=>'Campo Municipal Leganés'],
            ['local'=>$jAlc, 'visitante'=>$jFue, 'fecha'=>'2025-12-29 10:00:00', 'rl'=>3, 'rv'=>2, 'lugar'=>'Campo Municipal Alcobendas'],
            ['local'=>$jLeg, 'visitante'=>$jGet, 'fecha'=>'2025-12-29 12:00:00', 'rl'=>0, 'rv'=>1, 'lugar'=>'Campo Municipal Leganés'],
        ];

        foreach ($partidoT3 as $p) {
            Partido::create([
                'fechaHora'         => $p['fecha'],
                'lugar'             => $p['lugar'],
                'resultadoLocal'    => $p['rl'],
                'resultadoVisitante'=> $p['rv'],
                'estado'            => 'finalizado',
                'idTorneo'          => $t3->idTorneo,
                'idEquipoLocal'     => $p['local']->idEquipo,
                'idEquipoVisitante' => $p['visitante']->idEquipo,
            ]);
        }
    }
}
