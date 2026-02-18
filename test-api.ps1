# ============================================
# Tests API - Gestor de Torneos de Fútbol
# Ejecuta estos comandos desde PowerShell
# ============================================

# CONFIGURACIÓN
$baseUrl = "http://127.0.0.1:8000/api"
$token = ""

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "API Tests - Gestor de Torneos" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# ============================================
# 1. REGISTRO - Crear nuevo usuario
# ============================================
Write-Host "1. Registrando nuevo usuario..." -ForegroundColor Yellow
$registroBody = @{
    nombre = "Carlos"
    apellidos = "Martínez"
    telefono = "612345678"
    email = "carlos@example.com"
    password = "12345678"
    password_confirmation = "12345678"
} | ConvertTo-Json

$registro = Invoke-RestMethod -Uri "$baseUrl/register" -Method Post -Body $registroBody -ContentType "application/json"
Write-Host "✓ Usuario registrado:" -ForegroundColor Green
Write-Host "  Email: $($registro.user.email)"
Write-Host "  Rol: $($registro.user.rol)"
Write-Host "  Token: $($registro.token.Substring(0,20))..." -ForegroundColor Gray
Write-Host ""

# ============================================
# 2. LOGIN - Obtener token de admin
# ============================================
Write-Host "2. Login como ADMIN..." -ForegroundColor Yellow
$loginBody = @{
    email = "admin@tourneypro.com"
    password = "1234"
} | ConvertTo-Json

$login = Invoke-RestMethod -Uri "$baseUrl/login" -Method Post -Body $loginBody -ContentType "application/json"
$token = $login.token
Write-Host "✓ Login exitoso:" -ForegroundColor Green
Write-Host "  Usuario: $($login.user.nombre) $($login.user.apellidos)"
Write-Host "  Rol: $($login.user.rol)"
Write-Host "  Token guardado" -ForegroundColor Gray
Write-Host ""

# ============================================
# 3. VER MI PERFIL
# ============================================
Write-Host "3. Obteniendo mi perfil..." -ForegroundColor Yellow
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
    "Content-Type" = "application/json; charset=utf-8"
}
$perfil = Invoke-RestMethod -Uri "$baseUrl/user" -Method Get -Headers $headers
Write-Host "✓ Perfil obtenido:" -ForegroundColor Green
Write-Host "  Nombre: $($perfil.nombre) $($perfil.apellidos)"
Write-Host "  Email: $($perfil.email)"
Write-Host "  Rol: $($perfil.rol)"
Write-Host ""

# ============================================
# 4. LISTAR TORNEOS
# ============================================
Write-Host "4. Listando torneos..." -ForegroundColor Yellow
$torneos = Invoke-RestMethod -Uri "$baseUrl/torneos" -Method Get -Headers $headers
Write-Host "✓ Torneos encontrados: $($torneos.data.Count)" -ForegroundColor Green
foreach ($torneo in $torneos.data) {
    Write-Host "  - [$($torneo.idTorneo)] $($torneo.nombre) ($($torneo.estado))"
}
Write-Host ""

# ============================================
# 5. CREAR TORNEO
# ============================================
Write-Host "5. Creando nuevo torneo..." -ForegroundColor Yellow
$torneoBody = @{
    nombre = "Copa PowerShell 2026"
    deporte = "Futbol"  # Evitar acentos para compatibilidad
    categoria = "Senior"
    formato = "Liga"
    fechaInicio = "2026-04-01"
    fechaFin = "2026-06-30"
    estado = "Inscripcion"
} | ConvertTo-Json -Depth 10

$nuevoTorneo = Invoke-RestMethod -Uri "$baseUrl/torneos" -Method Post -Body $torneoBody -Headers $headers
Write-Host "✓ Torneo creado:" -ForegroundColor Green
Write-Host "  ID: $($nuevoTorneo.idTorneo)"
Write-Host "  Nombre: $($nuevoTorneo.nombre)"
Write-Host "  Estado: $($nuevoTorneo.estado)"
Write-Host "  Creado por: $($nuevoTorneo.usuario_creador.nombre)" -ForegroundColor Gray
Write-Host ""

# ============================================
# 6. LISTAR EQUIPOS
# ============================================
Write-Host "6. Listando equipos..." -ForegroundColor Yellow
$equipos = Invoke-RestMethod -Uri "$baseUrl/equipos" -Method Get -Headers $headers
Write-Host "✓ Equipos encontrados: $($equipos.Count)" -ForegroundColor Green
foreach ($equipo in $equipos) {
    Write-Host "  - [$($equipo.idEquipo)] $($equipo.nombre)"
}
Write-Host ""

# ============================================
# 7. CREAR EQUIPO
# ============================================
Write-Host "7. Creando nuevo equipo..." -ForegroundColor Yellow
$equipoBody = @{
    nombre = "PowerShell FC"
    logo = "https://example.com/powershell-fc.png"
    categoria = "Senior"
} | ConvertTo-Json -Depth 10

$nuevoEquipo = Invoke-RestMethod -Uri "$baseUrl/equipos" -Method Post -Body $equipoBody -Headers $headers
Write-Host "✓ Equipo creado:" -ForegroundColor Green
Write-Host "  ID: $($nuevoEquipo.idEquipo)"
Write-Host "  Nombre: $($nuevoEquipo.nombre)"
Write-Host ""

# ============================================
# 8. LISTAR PARTIDOS
# ============================================
Write-Host "8. Listando partidos..." -ForegroundColor Yellow
$partidos = Invoke-RestMethod -Uri "$baseUrl/partidos" -Method Get -Headers $headers
Write-Host "✓ Partidos encontrados: $($partidos.data.Count)" -ForegroundColor Green
foreach ($partido in $partidos.data) {
    $local = $partido.equipo_local.nombre
    $visitante = $partido.equipo_visitante.nombre
    Write-Host "  - [$($partido.idPartido)] $local vs $visitante - $($partido.estado)"
}
Write-Host ""

# ============================================
# 9. LISTAR CLASIFICACIONES
# ============================================
Write-Host "9. Listando clasificaciones..." -ForegroundColor Yellow
$clasificaciones = Invoke-RestMethod -Uri "$baseUrl/clasificaciones" -Method Get -Headers $headers
Write-Host "✓ Clasificaciones encontradas: $($clasificaciones.data.Count)" -ForegroundColor Green
foreach ($clas in $clasificaciones.data) {
    Write-Host "  - $($clas.equipo.nombre): $($clas.puntos) pts ($($clas.victorias)V-$($clas.empates)E-$($clas.derrotas)D)"
}
Write-Host ""

# ============================================
# RESUMEN FINAL
# ============================================
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "✓ TODOS LOS TESTS COMPLETADOS" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Token de sesión guardado en variable:" -ForegroundColor Yellow
Write-Host '$token = "' -NoNewline -ForegroundColor Gray
Write-Host $token.Substring(0,50) -NoNewline -ForegroundColor Gray
Write-Host '..."' -ForegroundColor Gray
Write-Host ""
Write-Host "Puedes usar este token para hacer más peticiones:" -ForegroundColor Yellow
Write-Host 'Invoke-RestMethod -Uri "$baseUrl/torneos" -Headers @{"Authorization"="Bearer $token"}' -ForegroundColor Gray
