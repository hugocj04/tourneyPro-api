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
        Schema::create('torneos', function (Blueprint $table) {
            $table->id('idTorneo');
            $table->string('nombre');
            $table->string('deporte');
            $table->string('categoria');
            $table->string('formato');
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->string('estado');
            $table->foreignId('idAdmin')->constrained('administradores', 'idAdmin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
