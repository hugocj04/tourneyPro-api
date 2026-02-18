<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            // Agregar nuevo campo fechaHora
            $table->dateTime('fechaHora')->nullable()->after('idPartido');
        });
        
        // Migrar datos existentes: combinar fecha + hora en fechaHora
        DB::table('partidos')->get()->each(function ($partido) {
            DB::table('partidos')
                ->where('idPartido', $partido->idPartido)
                ->update([
                    'fechaHora' => $partido->fecha . ' ' . $partido->hora
                ]);
        });
        
        Schema::table('partidos', function (Blueprint $table) {
            // Hacer el campo obligatorio ahora que tiene datos
            $table->dateTime('fechaHora')->nullable(false)->change();
            // Eliminar campos antiguos
            $table->dropColumn(['fecha', 'hora']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            // Restaurar campos fecha y hora
            $table->date('fecha')->after('idPartido');
            $table->time('hora')->after('fecha');
        });
        
        // Migrar datos de vuelta
        DB::table('partidos')->get()->each(function ($partido) {
            $fechaHora = \Carbon\Carbon::parse($partido->fechaHora);
            DB::table('partidos')
                ->where('idPartido', $partido->idPartido)
                ->update([
                    'fecha' => $fechaHora->toDateString(),
                    'hora' => $fechaHora->toTimeString(),
                ]);
        });
        
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropColumn('fechaHora');
        });
    }
};
