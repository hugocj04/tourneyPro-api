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
            'logo' => 'https://logodownload.org/wp-content/uploads/2016/03/real-madrid-logo-0.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'FC Barcelona',
            'logo' => 'https://upload.wikimedia.org/wikipedia/sco/thumb/4/47/FC_Barcelona_%28crest%29.svg/1010px-FC_Barcelona_%28crest%29.svg.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'Atlético Madrid',
            'logo' => 'https://logodownload.org/wp-content/uploads/2017/02/atletico-madrid-logo-0.png',
            'categoria' => 'Senior',
        ]);

        Equipo::create([
            'nombre' => 'Valencia CF',
            'logo' => 'https://assets.football-logos.cc/logos/spain/700x700/valencia.b55bb96d.png',
            'categoria' => 'Senior',
        ]);
    }
}
