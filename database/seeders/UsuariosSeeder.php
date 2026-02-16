<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Principal',
            'email' => 'admin@tourneypro.com',
            'contraseña' => '1234',
            'fechaRegistro' => now(),
        ]);
    }
}
