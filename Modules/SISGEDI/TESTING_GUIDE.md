# 📋 Guía de Prueba: Sistema de Evidencias y Firmas

## ✅ Requisitos Previos

- Tarea de prueba creada: **ID 7** - "Prueba: Informe de Actividades Semanal"
- Instructor con firma activa: **Dr. Juan López García** (v2)
- Colaborador asignado: **Carlos Testero** (ID: 5)

## 🎯 Objetivo

Verificar que el sistema completo de evidencias y firmas funciona:
1. ✓ Colaborador puede subir múltiples evidencias
2. ✓ Instructor puede revisar evidencias
3. ✓ Instructor puede firmar y aprobar con su firma digital
4. ✓ Sistema registra la firma y el documento en la BD

---

## 🚀 PASO 1: COLABORADOR SUBE EVIDENCIA

### Paso 1.1: Inicia sesión como Colaborador

1. Ve a: **http://127.0.0.1:8000/sisgedi**
2. Busca el campo de login
3. Ingresa ID de usuario: **5**
4. Haz clic en "Entrar" o presiona Enter
5. ✓ Deberías estar en el dashboard del colaborador

### Paso 1.2: Navega a Mis Tareas

1. En la barra lateral, haz clic en **"Mis Tareas"**
2. Ve a: **http://127.0.0.1:8000/sisgedi/colaborador/mis-tareas**
3. Deberías ver una tabla con tareas

### Paso 1.3: Busca la Tarea de Prueba

En la tabla, busca la fila con:
- **Tarea:** "Prueba: Informe de Actividades Semanal"
- **Instructor:** "Dr. Juan López García"
- **Estado:** Naranja badge que dice "Pendiente"

### Paso 1.4: Sube la Primera Evidencia

1. En la fila de la tarea de prueba, haz clic en el botón verde **"+ Subir Evidencia"**
2. Aparecerá un formulario bajo la tabla
3. En el área punteada que dice "Haz clic para seleccionar archivo":
   - Haz clic para abrir el navegador de archivos
   - Selecciona CUALQUIER archivo (PDF, Word, Excel, Imagen, etc.)
   - **Máximo 10MB**
4. En el campo "Descripción (opcional)", escribe algo como:
   ```
   Informe semanal con los resultados de producción y actividades realizadas en la unidad.
   ```
5. Haz clic en el botón **"Subir Evidencia"** (verde)
6. ✓ **Esperado:** Mensaje en la parte superior: "Evidencia cargada exitosamente"

### Paso 1.5: Sube una Segunda Evidencia (Opcional)

1. Haz clic nuevamente en **"+ Subir Evidencia"**
2. Selecciona otro archivo diferente
3. Escribe: "Datos anexos con detalles de producción"
4. Haz clic en **"Subir Evidencia"**
5. ✓ **Esperado:** Ahora la tarea mostrará "2 archivo(s)" en la columna Evidencias

### Paso 1.6: Verifica que los Archivos Aparezcan

- En la columna "Evidencias" deberías ver:
  - "2 archivo(s)" (o el número que subiste)
  - Links azules con nombres de archivos (ej: "📎 Informe_Semanal.pdf")
  - Botón pequeño de "✕" para eliminar si quieres

---

## 📥 PASO 2: INSTRUCTOR REVISA Y FIRMA

### Paso 2.1: Cierra Sesión del Colaborador

1. En la esquina superior derecha, haz clic en tu perfil o icono de usuario
2. Haz clic en **"Cerrar Sesión"** o **"Logout"**
3. ✓ Deberías estar en la página de login

### Paso 2.2: Inicia Sesión como Instructor

1. Ve a: **http://127.0.0.1:8000/sisgedi**
2. Ingresa ID de usuario: **1**
3. Haz clic en "Entrar"
4. ✓ Deberías estar en el dashboard del instructor

### Paso 2.3: Navega a Revisar Entregables

1. En la barra lateral, haz clic en **"Revisión de Entregables"**
2. Ve a: **http://127.0.0.1:8000/sisgedi/instructor/revisar-entregables**
3. Deberías ver dos secciones:
   - Izquierda: "Entregables Pendientes"
   - Derecha: "Detalle del Entregable Seleccionado"

### Paso 2.4: Selecciona la Tarea de Prueba

1. En la lista de entregables pendientes, busca:
   - **"Prueba: Informe de Actividades Semanal"**
   - **Colaborador:** "Carlos Testero"
2. Haz clic en esa fila (debe destacarse en verde)
3. ✓ En la sección derecha aparecerán los detalles:
   - Nombre del entregable
   - Colaborador
   - Tipo de archivo
   - Una vista previa del documento

### Paso 2.5: Descarga la Evidencia para Revisar

1. En la sección derecha, deberías ver un botón azul:
   **"Descargar para revisar"**
2. Haz clic en él para descargar el archivo
3. Abre el archivo en tu programa de lectura (PDF, Word, etc.)
4. Revisa que el contenido sea correcto
5. ✓ Cierra el archivo

### Paso 2.6: Aprueba y Firma el Entregable

1. Regresa a la página del navegador
2. En la sección de "Decisión del Instructor", verás dos botones:
   - Verde: **"Firmar y Aprobar"** ✓
   - Rojo: **"Rechazar Entregable"** ✕
3. Haz clic en el botón **verde "Firmar y Aprobar"**
4. Aparecerá un diálogo de confirmación:
   ```
   ¿Deseas aprobar este entregable y firmarlo?
   ```
5. Haz clic en **"Aceptar"** o **"OK"**
6. ✓ **Esperado:** Mensaje de éxito en la parte superior:
   ```
   "Entregable aprobado y firmado exitosamente."
   ```

### Paso 2.7: Verifica que la Tarea Desapareció de Pendientes

1. La tarea debería desaparecer de la lista "Entregables Pendientes"
2. Si hay más tareas, la siguiente aparecerá automáticamente

---

## 🔏 PASO 3: VERIFICA LA FIRMA DIGITAL

### Paso 3.1: Navega a Gestionar Firma

1. En la barra lateral, haz clic en **"Mi Firma Digital"**
2. Ve a: **http://127.0.0.1:8000/sisgedi/instructor/gestionar-firma**
3. Deberías ver dos columnas:
   - Izquierda: "Firma Vigente"
   - Derecha: "Historial de Firmas"

### Paso 3.2: Verifica el Contador de Documentos Firmados

1. En la columna derecha "Historial de Firmas", busca:
   - **v2 (Vigente)** - badge verde que dice "Activa"
2. Deberías ver algo como:
   ```
   v2 (Vigente)
   Cargada 15 Sep 2026
   ✓ 1 docs firmados  ← ESTE NÚMERO DEBE AUMENTAR
   ```
3. Antes de la prueba: "0 docs firmados"
4. Después de aprobar: **"1 docs firmados"** ✓

---

## 🔄 PASO 4 (OPCIONAL): PRUEBA EL FLUJO DE RECHAZO

### Paso 4.1: Crea Otra Tarea de Prueba

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
php artisan sisgedi:test-task
```

Esto creará automáticamente otra tarea de prueba

### Paso 4.2: Colaborador Sube Evidencia

- Repite PASO 1 con la nueva tarea
- Sube una evidencia

### Paso 4.3: Instructor Rechaza con Feedback

1. Vuelve a `/sisgedi/instructor/revisar-entregables`
2. Selecciona la nueva tarea
3. Haz clic en el botón rojo **"Rechazar Entregable"**
4. Aparecerá un formulario con el campo "Motivo del Rechazo"
5. Escribe un comentario, ej:
   ```
   El documento no incluye el análisis comparativo solicitado.
   Por favor, revisa la guía y reenvía con los datos completos.
   ```
6. Haz clic en **"Confirmar Rechazo"** (rojo)
7. ✓ Mensaje: "Entregable rechazado. Se notificará al colaborador."

### Paso 4.4: Colaborador Ve el Rechazo

1. Cierra sesión del instructor
2. Inicia sesión como colaborador (ID: 5)
3. Ve a `/sisgedi/colaborador/mis-tareas`
4. La tarea rechazada ahora mostrará:
   - Estado: Badge rojo "✕ Rechazado"
   - Un recuadro rojo con el feedback del instructor
5. Puede hacer clic en **"+ Subir Evidencia"** nuevamente
6. Sube una versión corregida
7. ✓ Ahora la tarea tendrá 2 evidencias

---

## 📊 VERIFICACIÓN EN BASE DE DATOS

Para verificar manualmente en MySQL Workbench o CLI:

### Ver la Aprobación

```sql
SELECT * FROM approvals WHERE id = 7;
```

**Esperado después de aprobar:**
```
id: 7
status: 'aprobado'
signature_id: 2 (o el ID de la firma activa)
reviewed_at: 2026-09-15 15:48:30
```

### Ver las Evidencias

```sql
SELECT * FROM evidences WHERE approval_id = 7;
```

**Esperado:**
```
id: 1
approval_id: 7
colaborador_id: 5
file_name: 'tu_archivo.pdf' (o el nombre que subiste)
file_type: 'pdf'
file_size: 256 (tamaño en KB)
uploaded_at: 2026-09-15 15:47:00
```

### Ver Contador de Firmas

```sql
SELECT COUNT(*) as docs_firmados 
FROM approvals 
WHERE signature_id = 2 AND status = 'aprobado';
```

**Esperado:** `docs_firmados: 1` (aumenta con cada aprobación)

---

## ✅ CHECKLIST DE ÉXITO

- [ ] Colaborador logró iniciar sesión (ID: 5)
- [ ] Vio la tarea de prueba en mis-tareas
- [ ] Subió una evidencia exitosamente
- [ ] Vio mensaje "Evidencia cargada exitosamente"
- [ ] El contador de archivos aumentó a "1 archivo(s)"
- [ ] Instructor logró iniciar sesión (ID: 1)
- [ ] Vio la tarea en revisar-entregables
- [ ] Descargó la evidencia correctamente
- [ ] Aprobó y firmó sin errores
- [ ] Vio mensaje de aprobación exitosa
- [ ] La firma mostró "1 docs firmados"
- [ ] En la BD, `approvals.status = 'aprobado'`
- [ ] En la BD, `approvals.signature_id` está lleno
- [ ] En la BD, la evidencia está guardada en la tabla

---

## 🐛 TROUBLESHOOTING

### Error: "Por favor inicia sesión primero"
→ Asegúrate de estar logueado (ID: 5 para colaborador, ID: 1 para instructor)

### Error: "No tienes permiso para..."
→ Verifica que el ID de usuario sea correcto para el rol

### No veo la tarea en mis-tareas
→ Ejecuta: `php create_test_task.php` nuevamente
→ Recarga la página (Ctrl+R o Cmd+R)

### El botón "Subir Evidencia" no funciona
→ Revisa que hayas seleccionado un archivo
→ Verifica que el archivo sea menor a 10MB
→ Revisa que tengas permisos de escritura en `storage/evidences/`

### No puedo descargar la evidencia
→ Verifica que el archivo exista en `storage/evidences/`
→ Comprueba permisos de lectura en la carpeta

### La firma no muestra "1 docs firmados"
→ Recarga la página (F5)
→ Verifica en la BD que `signature_id` no sea NULL
→ Ejecuta: `SELECT COUNT(*) FROM approvals WHERE signature_id = 2 AND status = 'aprobado';`

---

## 📞 Soporte

Si algo no funciona:
1. Revisa los logs en `storage/logs/`
2. Ejecuta: `php artisan tinker` para verificar datos
3. Limpia cache: `php artisan view:clear`

¡Buen testing! 🎉
