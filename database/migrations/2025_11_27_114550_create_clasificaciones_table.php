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
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id('idClasificacion');
            $table->integer('puntos')->default(0);
            $table->integer('partidosJugados')->default(0);
            $table->integer('victorias')->default(0);
            $table->integer('empates')->default(0);
            $table->integer('derrotas')->default(0);
            $table->integer('golesFavor')->default(0);
            $table->integer('golesContra')->default(0);
            $table->foreignId('idEquipo')->constrained('equipos', 'IdEquipo');
            $table->foreignId('idTorneo')->constrained('torneos', 'idTorneo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasificaciones');
    }
};
