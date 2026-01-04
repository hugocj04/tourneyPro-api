<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Juan',
            'apellidos' => 'García Pérez',
            'email' => 'juan.garcia@example.com',
            'contraseña' => Hash::make('password123'),
            'fechaRegistro' => now(),
        ]);

        Usuario::create([
            'nombre' => 'María',
            'apellidos' => 'López Martínez',
            'email' => 'maria.lopez@example.com',
            'contraseña' => Hash::make('password123'),
            'fechaRegistro' => now(),
        ]);

        Usuario::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Rodríguez Sánchez',
            'email' => 'carlos.rodriguez@example.com',
            'contraseña' => Hash::make('password123'),
            'fechaRegistro' => now(),
        ]);

        Usuario::create([
            'nombre' => 'Ana',
            'apellidos' => 'Fernández Gómez',
            'email' => 'ana.fernandez@example.com',
            'contraseña' => Hash::make('password123'),
            'fechaRegistro' => now(),
        ]);

        Usuario::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Martín Díaz',
            'email' => 'pedro.martin@example.com',
            'contraseña' => Hash::make('password123'),
            'fechaRegistro' => now(),
        ]);
    }
}
