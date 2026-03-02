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
        Schema::create('estadisticas_jugadores', function (Blueprint $table) {
            $table->id('idEstadisticaJugador');
            $table->unsignedBigInteger('idJugador');
            $table->unsignedBigInteger('idTorneo');
            $table->integer('goles')->default(0);
            $table->integer('partidosJugados')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('idJugador')->references('idJugador')->on('jugadores')->onDelete('cascade');
            $table->foreign('idTorneo')->references('idTorneo')->on('torneos')->onDelete('cascade');

            // Unique constraint: un jugador solo puede tener una estadística por torneo
            $table->unique(['idJugador', 'idTorneo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas_jugadores');
    }
};
