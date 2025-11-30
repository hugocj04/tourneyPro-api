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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id('idPartido');
            $table->date('fecha');
            $table->time('hora');
            $table->string('lugar');
            $table->integer('resultadoLocal')->nullable();
            $table->integer('resultadoVisitante')->nullable();
            $table->string('estado');
            $table->foreignId('idTorneo')->constrained('torneos', 'idTorneo');
            $table->foreignId('idEquipoLocal')->constrained('equipos', 'IdEquipo');
            $table->foreignId('idEquipoVisitante')->constrained('equipos', 'IdEquipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
