<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jugadores')) {
            Schema::create('jugadores', function (Blueprint $table) {
                $table->id();
                $table->integer('dorsal');
                $table->string('posicion');
                $table->foreignId('idUsuario')->constrained('usuarios', 'idUsuario');
                $table->foreignId('IdEquipo')->constrained('equipos', 'IdEquipo');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
