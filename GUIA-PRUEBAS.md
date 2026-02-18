# 🧪 GUÍA DE PRUEBAS - API Gestor de Torneos

## 📋 ¿Cómo probar la API sin app móvil?

Tienes **3 opciones** para probar la API:

---

## ✅ OPCIÓN 1: REST Client (VS Code) - **MÁS FÁCIL**

### 1. Instalar extensión REST Client
- Abre VS Code
- Ve a Extensiones (Ctrl + Shift + X)
- Busca "REST Client" por Huachao Mao
- Click en "Instalar"

### 2. Usar el archivo de pruebas
- Abre el archivo `api-tests.http`
- Verás links azules que dicen "Send Request"
- Click en "Send Request" sobre cada petición

### 3. Flujo de prueba
```
1. Haz click en "Send Request" de LOGIN (Admin)
2. Copia el token de la respuesta
3. Pega el token en la línea 9: @token = aqui_tu_token
4. Ahora puedes hacer click en los demás "Send Request"
```

**✨ Ventajas:** Visual, fácil, resultados organizados

---

## ✅ OPCIÓN 2: PowerShell Script - **AUTOMÁTICO**

### 1. Ejecutar el script
```powershell
# Navega a la carpeta del proyecto
cd C:\Users\carmona.jihug24_tria\Desktop\laravel-api

# Ejecuta el script de pruebas
.\test-api.ps1
```

### 2. Qué hace el script
- ✓ Registra un usuario nuevo
- ✓ Hace login como admin
- ✓ Lista torneos existentes
- ✓ Crea un nuevo torneo
- ✓ Lista equipos
- ✓ Crea un equipo
- ✓ Lista partidos y clasificaciones
- ✓ Muestra resumen con colores

**✨ Ventajas:** Automático, prueba todo de una vez

---

## ✅ OPCIÓN 3: Postman - **MÁS COMPLETO**

### 1. Descargar Postman
- Descarga de: https://www.postman.com/downloads/
- Instala y abre Postman

### 2. Crear colección
- New → HTTP Request
- Guarda tus peticiones en una colección

### 3. Ejemplo: Login
```
Method: POST
URL: http://127.0.0.1:8000/api/login
Headers: Content-Type: application/json
Body (raw - JSON):
{
  "email": "admin@tourneypro.com",
  "password": "1234"
}
```

### 4. Guardar el token
- Después del login, copia el token de la respuesta
- En la pestaña "Authorization" de otras peticiones:
  - Type: Bearer Token
  - Token: pega_tu_token_aqui

**✨ Ventajas:** Potente, guarda historial, fácil de compartir

---

## 🚀 PRUEBAS PASO A PASO (Manual)

### 📍 PASO 1: Verificar que el servidor está corriendo

```powershell
# Debería estar corriendo en otra terminal
# Si no está corriendo:
php artisan serve
```

Verás: `Server started on http://127.0.0.1:8000`

---

### 📍 PASO 2: Hacer LOGIN

**Con PowerShell:**
```powershell
$body = @{
    email = "admin@tourneypro.com"
    password = "1234"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/login" -Method Post -Body $body -ContentType "application/json"

# Ver respuesta
$response

# Guardar token
$token = $response.token
Write-Host "Token: $token"
```

**Respuesta esperada:**
```json
{
  "token": "1|abcd1234...token_largo...",
  "user": {
    "idUsuario": 1,
    "nombre": "Admin",
    "apellidos": "Principal",
    "email": "admin@tourneypro.com",
    "rol": "admin"
  }
}
```

✅ **GUARDA EL TOKEN** - Lo necesitarás para las demás peticiones

---

### 📍 PASO 3: Listar TORNEOS

```powershell
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

$torneos = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/torneos" -Method Get -Headers $headers
$torneos.data
```

**Como admin** verás TODOS los torneos.
**Como usuario** solo verás TUS torneos.

---

### 📍 PASO 4: Crear un TORNEO

```powershell
$nuevoTorneo = @{
    nombre = "Mi Primer Torneo"
    deporte = "Fútbol"
    categoria = "Senior"
    formato = "Liga"
    fechaInicio = "2026-03-01"
    fechaFin = "2026-05-30"
    estado = "Inscripcion"
} | ConvertTo-Json

$result = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/torneos" -Method Post -Body $nuevoTorneo -ContentType "application/json" -Headers $headers
$result
```

✅ El campo `idUsuarioCreador` se asigna automáticamente

---

### 📍 PASO 5: Ver UN torneo específico

```powershell
# Ver torneo con ID 1
$torneo = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/torneos/1" -Method Get -Headers $headers
$torneo
```

Incluye relaciones: `usuario_creador`, `partidos`, `clasificaciones`

---

### 📍 PASO 6: Crear un EQUIPO

```powershell
$nuevoEquipo = @{
    nombre = "Real Madrid"
    logo = "https://example.com/logo.png"
    categoria = "Senior"
} | ConvertTo-Json

$equipo = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/equipos" -Method Post -Body $nuevoEquipo -ContentType "application/json" -Headers $headers
$equipo
```

---

### 📍 PASO 7: Crear un PARTIDO

```powershell
$nuevoPartido = @{
    fecha = "2026-03-20"
    hora = "18:00"
    lugar = "Estadio Municipal"
    estado = "Pendiente"
    idTorneo = 1
    idEquipoLocal = 1
    idEquipoVisitante = 2
} | ConvertTo-Json

$partido = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/partidos" -Method Post -Body $nuevoPartido -ContentType "application/json" -Headers $headers
$partido
```

---

### 📍 PASO 8: Actualizar RESULTADO de un partido

```powershell
$resultado = @{
    resultadoLocal = 3
    resultadoVisitante = 1
    estado = "Finalizado"
} | ConvertTo-Json

$partidoActualizado = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/partidos/1" -Method Put -Body $resultado -ContentType "application/json" -Headers $headers
$partidoActualizado
```

---

## 🎯 ESCENARIOS DE PRUEBA RECOMENDADOS

### ✅ Escenario 1: Usuario crea su primer torneo
1. Registrarse con `/register`
2. Hacer login
3. Crear torneo
4. Ver lista de torneos (solo verá el suyo)

### ✅ Escenario 2: Admin supervisa todo
1. Login como admin
2. Ver TODOS los torneos
3. Ver TODOS los equipos
4. Editar cualquier torneo

### ✅ Escenario 3: Probar autorización (debe fallar)
1. Login como usuario normal
2. Intentar ver torneo de otro usuario → ❌ 403 Forbidden
3. Intentar editar torneo de otro usuario → ❌ 403 Forbidden

---

## 🔐 CREDENCIALES DE PRUEBA

```
Admin:
  Email: admin@tourneypro.com
  Password: 1234
  Rol: admin (puede ver/editar TODO)

Usuario nuevo:
  Créalo con /register
  Rol: usuario (solo ve SUS torneos)
```

---

## 📊 ENDPOINTS DISPONIBLES

### Autenticación (Públicos)
- `POST /api/register` - Registrarse
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión

### Recursos (Requieren token)
- `GET/POST /api/torneos` - Listar/Crear torneos
- `GET/PUT/DELETE /api/torneos/{id}` - Ver/Editar/Eliminar torneo
- `GET/POST /api/equipos` - Listar/Crear equipos
- `GET/PUT/DELETE /api/equipos/{id}` - Ver/Editar/Eliminar equipo
- `GET/POST /api/jugadores` - Listar/Crear jugadores
- `GET/POST /api/partidos` - Listar/Crear partidos
- `GET /api/clasificaciones` - Ver clasificaciones
- `GET/POST /api/notificaciones` - Ver/Crear notificaciones

---

## ❗ PROBLEMAS COMUNES

### Error: "Unauthenticated"
**Causa:** No enviaste el token o está mal
**Solución:** Verifica que el header Authorization esté correcto

### Error: "403 Forbidden"
**Causa:** No tienes permiso (intentas ver/editar algo que no es tuyo)
**Solución:** Esto es correcto, la autorización está funcionando

### Error: "404 Not Found"
**Causa:** El ID no existe
**Solución:** Verifica que el recurso exista con GET

### Error: "422 Unprocessable Entity"
**Causa:** Validación falló (datos incorrectos)
**Solución:** Revisa los campos requeridos en la respuesta

---

## 📝 COMANDOS ÚTILES

```powershell
# Ver logs en tiempo real
Get-Content storage\logs\laravel.log -Wait -Tail 50

# Ver todas las rutas
php artisan route:list --path=api

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Reiniciar base de datos
php artisan migrate:fresh --seed
```

---

## 🎉 ¡LISTO PARA PROBAR!

Usa cualquiera de las 3 opciones:
1. ✨ **REST Client** (VS Code) → Más fácil y visual
2. 🚀 **PowerShell Script** → Automático
3. 💪 **Postman** → Más completo

¡Empieza con la opción que te resulte más cómoda!
