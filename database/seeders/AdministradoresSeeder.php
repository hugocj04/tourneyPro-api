<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrador;
use App\Models\Usuario;

class AdministradoresSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = Usuario::where('email', 'admin@tourneypro.com')->first();

        if ($usuario) {
            Administrador::create([
                'telefonoContacto' => '+34 600 123 456',
                'organizacion' => 'Administración Principal',
                'idUsuario' => $usuario->idUsuario,
            ]);
        }
    }
}
