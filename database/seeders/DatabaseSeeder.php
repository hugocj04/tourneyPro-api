<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UsuariosSeeder::class,
            EquiposSeeder::class,
            TorneosSeeder::class,
            NotificacionesSeeder::class,
            JugadoresSeeder::class,
            ClasificacionesSeeder::class,
            PartidosSeeder::class,
            InscripcionEquiposSeeder::class,
            EventoPartidosSeeder::class,
        ]);
    }
}
