# 🧪 Sistema de Evidencias y Firmas - Guía de Prueba Rápida

## ⚡ Inicio Rápido (3 minutos)

### 1️⃣ Crear Tarea de Prueba

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
php artisan sisgedi:test-task
```

**Salida esperada:**
```
═════════════════════════════════════════════
  Creando Tarea de Prueba - SISGEDI Testing
═════════════════════════════════════════════
✓ Tarea creada exitosamente
  ID: 8
  Colaborador: Carlos Testero (ID: 5)
  Instructor: Dr. Juan López García (ID: 1)
```

### 2️⃣ Prueba como Colaborador

1. Ve a: **http://127.0.0.1:8000/sisgedi**
2. Ingresa: **ID = 5**
3. Haz clic: **Mis Tareas**
4. Busca: **"Prueba: Informe de Actividades Semanal"**
5. Haz clic: **"+ Subir Evidencia"**
6. Selecciona: **Cualquier archivo** (PDF, Word, Excel, Imagen)
7. Haz clic: **"Subir Evidencia"** ✓

**Resultado esperado:**
```
✓ Evidencia cargada exitosamente. Archivo: tu_archivo.pdf
```

### 3️⃣ Prueba como Instructor

1. Logout (esquina superior derecha)
2. Ingresa: **ID = 1**
3. Haz clic: **Revisión de Entregables**
4. Busca: **"Prueba: Informe de Actividades Semanal"**
5. Haz clic: **"Firmar y Aprobar"** ✓
6. Confirma: **"Aceptar"**

**Resultado esperado:**
```
✓ Entregable aprobado y firmado exitosamente.
```

### 4️⃣ Verifica la Firma

1. Haz clic: **Mi Firma Digital**
2. En "Historial de Firmas", busca: **v2 (Vigente)**
3. Deberá mostrar: **"1 docs firmados"** ✓

---

## 📚 Documentación Completa

Para una guía paso a paso detallada, ver: **TESTING_GUIDE.md**

Incluye:
- ✓ Instrucciones detalladas con capturas
- ✓ Pruebas de rechazo y resubmisión
- ✓ Verificación en base de datos
- ✓ Troubleshooting y solución de errores

---

## 🎯 Qué se Prueba

| Feature | Descripción | Estado |
|---------|------------|--------|
| **Upload** | Colaborador sube evidencia (archivos múltiples) | ✓ |
| **Download** | Instructor descarga evidencia para revisar | ✓ |
| **Firma Digital** | Instructor aprueba con su firma digital | ✓ |
| **Contador** | Sistema cuenta documentos firmados | ✓ |
| **Rechazo** | Instructor rechaza con feedback | ✓ |
| **Resubmisión** | Colaborador reenvía después de rechazo | ✓ |
| **Base de Datos** | Datos persisten correctamente | ✓ |

---

## 🚀 Comandos Útiles

```bash
# Crear nueva tarea de prueba
php artisan sisgedi:test-task

# Ver todas las tareas pendientes
php artisan tinker
>>> DB::table('approvals')->where('status', 'pendiente')->get();

# Ver todas las evidencias
>>> DB::table('evidences')->get();

# Limpiar cache
php artisan view:clear
php artisan config:clear

# Ver logs
tail -f storage/logs/laravel.log
```

---

## 📊 Datos de Prueba Incluidos

**Colaborador:** Carlos Testero (ID: 5)
**Instructor:** Dr. Juan López García (ID: 1)
**Firma Instructor:** v2 (Activa)
**Tarea:** Prueba: Informe de Actividades Semanal

---

## ✅ Checklist de Éxito

- [ ] Tarea de prueba creada con `php artisan sisgedi:test-task`
- [ ] Colaborador logró iniciar sesión (ID: 5)
- [ ] Colaborador vio la tarea en mis-tareas
- [ ] Colaborador subió archivo exitosamente
- [ ] Instructor logró iniciar sesión (ID: 1)
- [ ] Instructor vio tarea en revisar-entregables
- [ ] Instructor aprobó y firmó sin errores
- [ ] Sistema mostró "1 docs firmados"
- [ ] Datos se guardaron en la BD

---

## 🐛 Problemas Comunes

| Problema | Solución |
|----------|----------|
| "Por favor inicia sesión primero" | Verifica que estés logueado (ID: 5 o 1) |
| No veo la tarea | Ejecuta: `php artisan sisgedi:test-task` nuevamente |
| No puedo subir archivo | Verifica tamaño < 10MB y formato válido |
| Botón "Firmar y Aprobar" deshabilitado | Carga una evidencia primero como colaborador |
| No aparece "1 docs firmados" | Recarga la página (F5) |

---

¡Listo para probar! 🎉
