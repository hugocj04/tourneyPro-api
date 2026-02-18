<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=" . str_repeat("=", 70) . "\n";
echo "TEST: Manejo de Errores - Exception Handler\n";
echo "=" . str_repeat("=", 70) . "\n\n";

// Login para obtener token
echo "🔑 Obteniendo token de autenticación...\n";
$loginBody = json_encode(['email' => 'admin@tourneypro.com', 'password' => '1234']);
$ch = curl_init('http://127.0.0.1:8000/api/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $loginBody);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['token'])) {
    die("❌ Error: No se pudo obtener token\n");
}

$token = $response['token'];
echo "✅ Token obtenido\n\n";

// Headers para peticiones autenticadas
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token
];

// Test 1: Recurso no encontrado (404)
echo "1️⃣  TEST: ModelNotFoundException (404)\n";
echo "-" . str_repeat("-", 70) . "\n";
$ch = curl_init('http://127.0.0.1:8000/api/torneos/99999');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Código HTTP: $httpCode\n";
echo "Respuesta: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo ($httpCode === 404 && $response['success'] === false) ? "✅ CORRECTO\n\n" : "❌ ERROR\n\n";

// Test 2: Ruta no encontrada (404)
echo "2️⃣  TEST: NotFoundHttpException (404)\n";
echo "-" . str_repeat("-", 70) . "\n";
$ch = curl_init('http://127.0.0.1:8000/api/ruta-inexistente');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Código HTTP: $httpCode\n";
echo "Respuesta: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo ($httpCode === 404) ? "✅ CORRECTO\n\n" : "❌ ERROR\n\n";

// Test 3: Método no permitido (405)
echo "3️⃣  TEST: MethodNotAllowedHttpException (405)\n";
echo "-" . str_repeat("-", 70) . "\n";
$ch = curl_init('http://127.0.0.1:8000/api/torneos/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true); // POST en lugar de GET/PUT/DELETE
curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Código HTTP: $httpCode\n";
echo "Respuesta: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo ($httpCode === 405) ? "✅ CORRECTO\n\n" : "❌ ERROR\n\n";

// Test 4: Validación (422)
echo "4️⃣  TEST: ValidationException (422)\n";
echo "-" . str_repeat("-", 70) . "\n";
$invalidData = json_encode([
    'nombreTorneo' => '', // Requerido pero vacío
    'deporte' => 'deporteInvalido', // Valor no válido
]);
$ch = curl_init('http://127.0.0.1:8000/api/torneos');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $invalidData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Código HTTP: $httpCode\n";
echo "Respuesta: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo ($httpCode === 422 && isset($response['errors'])) ? "✅ CORRECTO\n\n" : "❌ ERROR\n\n";

// Test 5: No autenticado (401)
echo "5️⃣  TEST: AuthenticationException (401)\n";
echo "-" . str_repeat("-", 70) . "\n";
$headersNoAuth = ['Content-Type: application/json', 'Accept: application/json'];
$ch = curl_init('http://127.0.0.1:8000/api/torneos');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headersNoAuth);
$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Código HTTP: $httpCode\n";
echo "Respuesta: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo ($httpCode === 401) ? "✅ CORRECTO\n\n" : "❌ ERROR\n\n";

echo "=" . str_repeat("=", 70) . "\n";
echo "✅ TESTS DE MANEJO DE ERRORES COMPLETADOS\n";
echo "=" . str_repeat("=", 70) . "\n";
