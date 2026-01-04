<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipo;

class EquiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipo::create([
            'nombre' => 'Real Madrid',
            'logo' => 'real_madrid.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'FC Barcelona',
            'logo' => 'barcelona.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'Atlético Madrid',
            'logo' => 'atletico.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'Valencia CF',
            'logo' => 'valencia.png',
            'categoria' => 'Senior',
        ]);
    }
}
