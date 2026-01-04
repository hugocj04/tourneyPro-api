<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notificacion;
use App\Models\Usuario;
use App\Models\Administrador;

class NotificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = Usuario::all();
        $admin = Administrador::first();

        if ($usuarios->count() > 0 && $admin) {
            Notificacion::create([
                'titulo' => 'Bienvenido al torneo',
                'mensaje' => 'Te has registrado exitosamente en la Copa de España 2026',
                'fechaEnvio' => now(),
                'leida' => false,
                'idUsuario' => $usuarios->first()->idUsuario,
                'idAdmin' => $admin->idAdmin,
            ]);

            Notificacion::create([
                'titulo' => 'Próximo partido',
                'mensaje' => 'Tu próximo partido es el sábado a las 18:00',
                'fechaEnvio' => now(),
                'leida' => true,
                'idUsuario' => $usuarios->first()->idUsuario,
                'idAdmin' => $admin->idAdmin,
            ]);
        }
    }
}
