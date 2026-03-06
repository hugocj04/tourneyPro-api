<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            
            $table->dateTime('fechaHora')->nullable()->after('idPartido');
        });
        
        DB::table('partidos')->get()->each(function ($partido) {
            DB::table('partidos')
                ->where('idPartido', $partido->idPartido)
                ->update([
                    'fechaHora' => $partido->fecha . ' ' . $partido->hora
                ]);
        });
        
        Schema::table('partidos', function (Blueprint $table) {
            
            $table->dateTime('fechaHora')->nullable(false)->change();
            
            $table->dropColumn(['fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            
            $table->date('fecha')->after('idPartido');
            $table->time('hora')->after('fecha');
        });
        
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
