<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('torneos', function (Blueprint $table) {
            $table->enum('tipoFutbol', ['futbol_5', 'futbol_7', 'futbol_11'])->after('formato');
            $table->integer('maxEquipos')->nullable()->after('tipoFutbol');
            $table->decimal('precioInscripcion', 10, 2)->nullable()->after('maxEquipos');
            $table->text('descripcion')->nullable()->after('nombre');
            $table->string('ubicacion')->nullable()->after('descripcion');
            $table->string('imagenPortada')->nullable()->after('ubicacion');
        });
    }

    public function down(): void
    {
        Schema::table('torneos', function (Blueprint $table) {
            $table->dropColumn(['tipoFutbol', 'maxEquipos', 'precioInscripcion', 'descripcion', 'ubicacion', 'imagenPortada']);
        });
    }
};
