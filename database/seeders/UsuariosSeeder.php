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
        // Admin
        Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Principal',
            'email' => 'admin@tourneypro.com',
            'contraseña' => Hash::make('1234'),
            'rol' => 'admin',
            'fechaRegistro' => now()->subDays(120),
        ]);

        // Organizadores
        $organizadores = [
            ['nombre' => 'Carlos', 'apellidos' => 'Ramírez Vega',    'email' => 'carlos@example.com'],
            ['nombre' => 'Ana',    'apellidos' => 'Gómez Torres',    'email' => 'ana@example.com'],
            ['nombre' => 'Pedro',  'apellidos' => 'Martínez Ruiz',   'email' => 'pedro@example.com'],
        ];
        foreach ($organizadores as $i => $o) {
            Usuario::create([
                'nombre'        => $o['nombre'],
                'apellidos'     => $o['apellidos'],
                'email'         => $o['email'],
                'contraseña'    => Hash::make('1234'),
                'rol'           => 'organizador',
                'fechaRegistro' => now()->subDays(90 - $i * 10),
            ]);
        }

        // Jugadores
        $jugadores = [
            ['nombre' => 'Juan',      'apellidos' => 'Pérez García',     'email' => 'juan@example.com'],
            ['nombre' => 'María',     'apellidos' => 'López Martínez',   'email' => 'maria@example.com'],
            ['nombre' => 'Luis',      'apellidos' => 'Fernández Díaz',   'email' => 'luis@example.com'],
            ['nombre' => 'Sofía',     'apellidos' => 'Hernández Mora',   'email' => 'sofia@example.com'],
            ['nombre' => 'Miguel',    'apellidos' => 'García Blanco',    'email' => 'miguel@example.com'],
            ['nombre' => 'Laura',     'apellidos' => 'Sánchez Prieto',   'email' => 'laura@example.com'],
            ['nombre' => 'Diego',     'apellidos' => 'Torres Alonso',    'email' => 'diego@example.com'],
            ['nombre' => 'Elena',     'apellidos' => 'Navarro Gil',      'email' => 'elena@example.com'],
            ['nombre' => 'Roberto',   'apellidos' => 'Molina Castro',    'email' => 'roberto@example.com'],
            ['nombre' => 'Patricia',  'apellidos' => 'Ramos Iglesias',   'email' => 'patricia@example.com'],
            ['nombre' => 'Andrés',    'apellidos' => 'Ortega Vargas',    'email' => 'andres@example.com'],
            ['nombre' => 'Carmen',    'apellidos' => 'Jiménez Serrano',  'email' => 'carmen@example.com'],
        ];
        foreach ($jugadores as $i => $j) {
            Usuario::create([
                'nombre'        => $j['nombre'],
                'apellidos'     => $j['apellidos'],
                'email'         => $j['email'],
                'contraseña'    => Hash::make('1234'),
                'rol'           => 'jugador',
                'fechaRegistro' => now()->subDays(60 - $i * 3),
            ]);
        }
    }
}
