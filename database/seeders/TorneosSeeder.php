<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\Usuario;

class TorneosSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::where('rol', 'admin')->first();
        $org   = Usuario::where('rol', 'organizador')->first();

        if (!$admin) return;

        $torneos = [
            // Activo (Liga en curso)
            [
                'nombre'           => 'Liga Regional Senior 2026',
                'descripcion'      => 'Liga regional con formato todos contra todos. 8 equipos Senior.',
                'ubicacion'        => 'Barcelona, España',
                'imagenPortada'    => 'https://example.com/liga-regional.jpg',
                'deporte'          => 'Fútbol',
                'categoria'        => 'Senior',
                'formato'          => 'Liga',
                'tipoFutbol'       => 'futbol_11',
                'maxEquipos'       => 8,
                'precioInscripcion'=> 100.00,
                'fechaInicio'      => '2026-01-10',
                'fechaFin'         => '2026-06-30',
                'estado'           => 'activo',
                'idUsuarioCreador' => $admin->idUsuario,
            ],
            // Activo (Cup en curso)
            [
                'nombre'           => 'Copa de España 2026',
                'descripcion'      => 'Torneo nacional de eliminación directa. Equipos Senior de toda España.',
                'ubicacion'        => 'Madrid, España',
                'imagenPortada'    => 'https://example.com/copa-espana.jpg',
                'deporte'          => 'Fútbol',
                'categoria'        => 'Senior',
                'formato'          => 'Eliminación Directa',
                'tipoFutbol'       => 'futbol_11',
                'maxEquipos'       => 16,
                'precioInscripcion'=> 200.00,
                'fechaInicio'      => '2026-02-01',
                'fechaFin'         => '2026-05-15',
                'estado'           => 'activo',
                'idUsuarioCreador' => $org ? $org->idUsuario : $admin->idUsuario,
            ],
            // Finalizado
            [
                'nombre'           => 'Torneo de Navidad 2025',
                'descripcion'      => 'Torneo amistoso de fútbol 7 en categoría Juvenil.',
                'ubicacion'        => 'Valencia, España',
                'imagenPortada'    => 'https://example.com/navidad-2025.jpg',
                'deporte'          => 'Fútbol',
                'categoria'        => 'Juvenil',
                'formato'          => 'Liga',
                'tipoFutbol'       => 'futbol_7',
                'maxEquipos'       => 4,
                'precioInscripcion'=> 50.00,
                'fechaInicio'      => '2025-12-20',
                'fechaFin'         => '2025-12-30',
                'estado'           => 'finalizado',
                'idUsuarioCreador' => $admin->idUsuario,
            ],
            // Pendiente (próximo)
            [
                'nombre'           => 'Torneo Verano Senior 2026',
                'descripcion'      => 'Torneo de verano en modalidad fútbol 5. Categoría Senior.',
                'ubicacion'        => 'Sevilla, España',
                'imagenPortada'    => null,
                'deporte'          => 'Fútbol',
                'categoria'        => 'Senior',
                'formato'          => 'Grupos + Eliminatoria',
                'tipoFutbol'       => 'futbol_5',
                'maxEquipos'       => 8,
                'precioInscripcion'=> 75.00,
                'fechaInicio'      => '2026-07-01',
                'fechaFin'         => '2026-08-31',
                'estado'           => 'pendiente',
                'idUsuarioCreador' => $org ? $org->idUsuario : $admin->idUsuario,
            ],
            // Cancelado
            [
                'nombre'           => 'Copa Primavera 2026',
                'descripcion'      => 'Cancelado por falta de equipos inscritos.',
                'ubicacion'        => 'Bilbao, España',
                'imagenPortada'    => null,
                'deporte'          => 'Fútbol',
                'categoria'        => 'Juvenil',
                'formato'          => 'Eliminación Directa',
                'tipoFutbol'       => 'futbol_7',
                'maxEquipos'       => 8,
                'precioInscripcion'=> 60.00,
                'fechaInicio'      => '2026-04-01',
                'fechaFin'         => '2026-04-30',
                'estado'           => 'cancelado',
                'idUsuarioCreador' => $admin->idUsuario,
            ],
        ];

        foreach ($torneos as $torneo) {
            Torneo::create($torneo);
        }
    }
}
