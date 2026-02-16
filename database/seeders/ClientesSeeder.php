<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Usuario::limit(3)->get();

        foreach ($usuarios as $usuario) {
            Cliente::create([
                'idUsuario' => $usuario->idUsuario,
                'direccion' => fake()->address(),
                'telefono' => fake()->phoneNumber(),
                'empresa' => fake()->company(),
            ]);
        }
    }
}
