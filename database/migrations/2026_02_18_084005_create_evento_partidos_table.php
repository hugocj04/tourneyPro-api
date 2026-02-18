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
        Schema::create('evento_partidos', function (Blueprint $table) {
            $table->id('idEvento');
            $table->foreignId('idPartido')->constrained('partidos', 'idPartido')->onDelete('cascade');
            $table->unsignedBigInteger('idJugador')->nullable();
            $table->foreignId('idEquipo')->constrained('equipos', 'idEquipo')->onDelete('cascade');
            $table->enum('tipoEvento', ['gol', 'tarjeta_amarilla', 'tarjeta_roja', 'cambio', 'autogol', 'penal_fallado', 'lesion']);
            $table->integer('minuto');
            $table->string('descripcion')->nullable();
            $table->timestamps();
            
            // Foreign key para jugador (nullable)
            $table->foreign('idJugador')->references('idJugador')->on('jugadores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_partidos');
    }
};
