<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrador;
use App\Models\Usuario;

class AdministradoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario1 = Usuario::where('email', 'juan.garcia@example.com')->first();
        $usuario2 = Usuario::where('email', 'maria.lopez@example.com')->first();

        if ($usuario1) {
            Administrador::create([
                'telefonoContacto' => '+34 600 123 456',
                'organizacion' => 'Liga Nacional de Fútbol',
                'idUsuario' => $usuario1->idUsuario,
            ]);
        }

        if ($usuario2) {
            Administrador::create([
                'telefonoContacto' => '+34 600 789 012',
                'organizacion' => 'Federación Deportiva',
                'idUsuario' => $usuario2->idUsuario,
            ]);
        }
    }
}
