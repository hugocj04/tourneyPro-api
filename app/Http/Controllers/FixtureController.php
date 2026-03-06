<?php

namespace App\Http\Controllers;

use App\Services\GeneradorFixtureService;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    protected $generadorFixture;

    public function __construct(GeneradorFixtureService $generadorFixture)
    {
        $this->generadorFixture = $generadorFixture;
    }

    public function generar(Request $request)
    {
        $validated = $request->validate([
            'idTorneo' => 'required|exists:torneos,idTorneo',
            'fechaInicio' => 'required|date',
            'diasEntreFechas' => 'nullable|integer|min:1|max:30',
            'horaInicio' => 'nullable|date_format:H:i',
            'lugar' => 'nullable|string|max:255',
        ]);

        try {
            $resultado = $this->generadorFixture->generarFixture(
                $validated['idTorneo'],
                $validated['fechaInicio'],
                $validated['diasEntreFechas'] ?? 7,
                $validated['horaInicio'] ?? '15:00',
                $validated['lugar'] ?? 'Por definir'
            );

            return response()->json($resultado, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function limpiar(Request $request)
    {
        $validated = $request->validate([
            'idTorneo' => 'required|exists:torneos,idTorneo',
        ]);

        try {
            $this->generadorFixture->limpiarFixture($validated['idTorneo']);

            return response()->json([
                'success' => true,
                'message' => 'Fixture limpiado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
