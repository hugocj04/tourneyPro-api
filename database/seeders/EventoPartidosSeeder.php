<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventoPartido;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Jugador;

class EventoPartidosSeeder extends Seeder
{
    // Helper: create N goals for $equipo in $partido, cycling through $jugadores
    private function crearGoles(Partido $partido, Equipo $equipo, int $cantidad, array $minutos, array $jugadores): void
    {
        for ($i = 0; $i < $cantidad; $i++) {
            EventoPartido::create([
                'idPartido' => $partido->idPartido,
                'idEquipo'  => $equipo->idEquipo,
                'idJugador' => count($jugadores) ? $jugadores[$i % count($jugadores)]->idJugador : null,
                'tipoEvento' => 'gol',
                'minuto'    => $minutos[$i] ?? null,
            ]);
        }
    }

    public function run(): void
    {
        $equipos = Equipo::orderBy('idEquipo')->get();
        if ($equipos->count() < 12) return;

        $rm  = $equipos[0]; $bar = $equipos[1]; $atm = $equipos[2];
        $val = $equipos[3]; $sev = $equipos[4]; $rso = $equipos[5];
        $ath = $equipos[6]; $vil = $equipos[7];
        $jAlc = $equipos[8]; $jLeg = $equipos[9];
        $jGet = $equipos[10]; $jFue = $equipos[11];

        $jugRM  = Jugador::where('idEquipo', $rm->idEquipo)->get()->all();
        $jugBar = Jugador::where('idEquipo', $bar->idEquipo)->get()->all();
        $jugAtm = Jugador::where('idEquipo', $atm->idEquipo)->get()->all();
        $jugVal = Jugador::where('idEquipo', $val->idEquipo)->get()->all();
        $jugSev = Jugador::where('idEquipo', $sev->idEquipo)->get()->all();
        $jugRso = Jugador::where('idEquipo', $rso->idEquipo)->get()->all();

        // Mapa de partidos finalizados por sus equipos
        $findPartido = fn($local, $vis) => Partido::where('idEquipoLocal', $local->idEquipo)
            ->where('idEquipoVisitante', $vis->idEquipo)
            ->where('estado', 'finalizado')
            ->first();

        // ── T1 Liga Senior ───────────────────────────────────────
        // RM 2-1 Bar
        if ($p = $findPartido($rm, $bar)) {
            $this->crearGoles($p, $rm,  2, [14, 67], $jugRM);
            $this->crearGoles($p, $bar, 1, [38],     $jugBar);
        }
        // Atm 1-1 Val
        if ($p = $findPartido($atm, $val)) {
            $this->crearGoles($p, $atm, 1, [55], $jugAtm);
            $this->crearGoles($p, $val, 1, [82], $jugVal);
        }
        // Sev 3-0 Rso
        if ($p = $findPartido($sev, $rso)) {
            $this->crearGoles($p, $sev, 3, [10, 44, 78], $jugSev);
        }
        // Bar 0-2 Atm
        if ($p = $findPartido($bar, $atm)) {
            $this->crearGoles($p, $atm, 2, [27, 61], $jugAtm);
        }
        // Val 1-2 RM
        if ($p = $findPartido($val, $rm)) {
            $this->crearGoles($p, $val, 1, [33], $jugVal);
            $this->crearGoles($p, $rm,  2, [48, 85], $jugRM);
        }
        // Rso 1-1 Sev
        if ($p = $findPartido($rso, $sev)) {
            $this->crearGoles($p, $rso, 1, [22], $jugRso);
            $this->crearGoles($p, $sev, 1, [70], $jugSev);
        }

        // ── T2 Copa España ───────────────────────────────────────
        // RM 3-0 Ath
        if ($p = $findPartido($rm, $ath)) {
            $this->crearGoles($p, $rm, 3, [5, 30, 88], $jugRM);
        }
        // Atm 1-0 Vil
        if ($p = $findPartido($atm, $vil)) {
            $this->crearGoles($p, $atm, 1, [53], $jugAtm);
        }

        // ── T3 Navidad (juveniles, sin jugadores seed) ───────────
        $partidosJuveniles = Partido::where('estado', 'finalizado')
            ->whereIn('idEquipoLocal', [$jAlc->idEquipo, $jLeg->idEquipo, $jGet->idEquipo, $jFue->idEquipo])
            ->get();

        $minutosJuv = [[12, 34], [7, 56, 80], [18], [29, 63], [5, 44, 72], [37]];
        foreach ($partidosJuveniles as $i => $p) {
            $golesLocal = $p->resultadoLocal ?? 0;
            $golesVis   = $p->resultadoVisitante ?? 0;
            $mins       = $minutosJuv[$i % count($minutosJuv)];
            $minsL = array_slice($mins, 0, $golesLocal);
            $minsV = array_slice($mins, $golesLocal, $golesVis);
            $localEq = Equipo::find($p->idEquipoLocal);
            $visEq   = Equipo::find($p->idEquipoVisitante);
            $jugLocal = $localEq ? Jugador::where('idEquipo', $localEq->idEquipo)->get()->all() : [];
            $jugVis   = $visEq   ? Jugador::where('idEquipo', $visEq->idEquipo)->get()->all()   : [];
            if ($localEq && $golesLocal) $this->crearGoles($p, $localEq, $golesLocal, $minsL, $jugLocal);
            if ($visEq   && $golesVis)   $this->crearGoles($p, $visEq,   $golesVis,   $minsV, $jugVis);
        }
    }
}
