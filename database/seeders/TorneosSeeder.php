<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\Administrador;

class TorneosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Administrador::first();

        if ($admin) {
            Torneo::create([
                'nombre' => 'Copa de España 2026',
                'deporte' => 'Fútbol',
                'categoria' => 'Senior',
                'formato' => 'Eliminación Directa',
                'fechaInicio' => '2026-03-01',
                'fechaFin' => '2026-05-30',
                'estado' => 'Próximo',
                'idAdmin' => $admin->idAdmin,
            ]);

            Torneo::create([
                'nombre' => 'Liga Regional 2026',
                'deporte' => 'Fútbol',
                'categoria' => 'Senior',
                'formato' => 'Liga',
                'fechaInicio' => '2026-02-15',
                'fechaFin' => '2026-06-15',
                'estado' => 'En Curso',
                'idAdmin' => $admin->idAdmin,
            ]);
        }
    }
}
