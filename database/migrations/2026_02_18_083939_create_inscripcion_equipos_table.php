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
        Schema::create('inscripcion_equipos', function (Blueprint $table) {
            $table->id('idInscripcion');
            $table->foreignId('idTorneo')->constrained('torneos', 'idTorneo')->onDelete('cascade');
            $table->foreignId('idEquipo')->constrained('equipos', 'idEquipo')->onDelete('cascade');
            $table->timestamp('fechaInscripcion')->useCurrent();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->decimal('montoAbonado', 10, 2)->nullable();
            $table->timestamps();
            
            // Evitar inscripciones duplicadas
            $table->unique(['idTorneo', 'idEquipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_equipos');
    }
};
