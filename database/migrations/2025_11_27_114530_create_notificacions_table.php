<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('idNotificacion');
            $table->string('titulo');
            $table->text('mensaje');
            $table->dateTime('fechaEnvio');
            $table->boolean('leida')->default(false);
            $table->foreignId('idUsuario')->constrained('usuarios', 'idUsuario');
            $table->foreignId('idAdmin')->constrained('administradores', 'idAdmin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacions');
    }
};
