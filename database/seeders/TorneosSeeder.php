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

        if ($admin) {
            Torneo::create([
                'nombre' => 'Copa de España 2026',
                'descripcion' => 'Torneo nacional de eliminación directa',
                'ubicacion' => 'Madrid, España',
                'imagenPortada' => 'https://example.com/copa-espana.jpg',
                'deporte' => 'Futbol',
                'categoria' => 'Senior',
                'formato' => 'Eliminacion Directa',
                'tipoFutbol' => 'futbol_11',
                'maxEquipos' => 16,
                'precioInscripcion' => 150.00,
                'fechaInicio' => '2026-03-01',
                'fechaFin' => '2026-05-30',
                'estado' => 'Proximo',
                'idUsuarioCreador' => $admin->idUsuario,
            ]);

            Torneo::create([
                'nombre' => 'Liga Regional 2026',
                'descripcion' => 'Liga regional con formato todos contra todos',
                'ubicacion' => 'Barcelona, España',
                'imagenPortada' => 'https://example.com/liga-regional.jpg',
                'deporte' => 'Futbol',
                'categoria' => 'Senior',
                'formato' => 'Liga',
                'tipoFutbol' => 'futbol_7',
                'maxEquipos' => 12,
                'precioInscripcion' => 100.00,
                'fechaInicio' => '2026-02-15',
                'fechaFin' => '2026-06-15',
                'estado' => 'En Curso',
                'idUsuarioCreador' => $admin->idUsuario,
            ]);
        }
    }
}
