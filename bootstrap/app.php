<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejo de errores para rutas API
        $exceptions->render(function (Throwable $e, Request $request) {
            // Solo aplicar para rutas API
            if (!$request->is('api/*')) {
                return null;
            }

            // ValidationException - Errores de validación
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }

            // AuthenticationException - No autenticado
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Token inválido o expirado.',
                ], 401);
            }

            // AuthorizationException - No autorizado
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.',
                ], 403);
            }

            // ModelNotFoundException - Modelo no encontrado
            if ($e instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($e->getModel()));
                return response()->json([
                    'success' => false,
                    'message' => "No se encontró el recurso solicitado ($model).",
                ], 404);
            }

            // NotFoundHttpException - Ruta no encontrada
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint no encontrado.',
                ], 404);
            }

            // MethodNotAllowedHttpException - Método HTTP no permitido
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Método HTTP no permitido para este endpoint.',
                ], 405);
            }

            // Error genérico de servidor
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            
            return response()->json([
                'success' => false,
                'message' => config('app.debug') 
                    ? $e->getMessage() 
                    : 'Ha ocurrido un error en el servidor.',
                'error' => config('app.debug') ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ] : null,
            ], $statusCode >= 100 && $statusCode < 600 ? $statusCode : 500);
        });
    })->create();
