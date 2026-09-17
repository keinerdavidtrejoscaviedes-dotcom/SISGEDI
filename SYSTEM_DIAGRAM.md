# 📊 Diagrama del Sistema - Evidencias y Firmas

## 🔄 Flujo General del Sistema

```
┌─────────────────────────────────────────────────────────────────────┐
│                         SISGEDI SYSTEM FLOW                         │
└─────────────────────────────────────────────────────────────────────┘

FASE 1: COLABORADOR SUBE EVIDENCIA
═════════════════════════════════════════════════════════════════════

Colaborador (ID: 5)
        │
        │ 1. Login
        ↓
    /sisgedi (SISGEDI Index)
        │
        │ 2. Click "Mis Tareas"
        ↓
    /sisgedi/colaborador/mis-tareas (ColaboradorDashboardController::misTareas)
        │
        │ 3. Controller obtiene tareas de DB
        │   Queries:
        │   - Approval::getByColaborador(5)
        │   - Evidence::countByApproval(approval_id)
        │   - Evidence::getByApproval(approval_id)
        │
        │ 4. Ver tabla con tareas
        │   Estado: PENDIENTE
        │
        │ 5. Click "+ Subir Evidencia"
        ↓
    Form: Upload Evidencia
        │
        │ 6. Selecciona archivo (PDF, Excel, etc.)
        │   Máx: 10MB
        │   Formatos: PDF, DOCX, XLSX, PPTX, JPG, PNG, GIF, ZIP
        │
        │ 7. Click "Subir Evidencia"
        ↓
    POST /sisgedi/colaborador/evidences/{approval_id}/upload
        (EvidenceController::upload)
        │
        ├─ Verificar sesión
        ├─ Verificar permisos (¿Es el colaborador asignado?)
        ├─ Validar archivo (tamaño, tipo)
        ├─ Guardar en storage/evidences/
        ├─ Crear registro en tabla evidences
        └─ Retornar mensaje de éxito
        │
        │ ✓ Mensaje: "Evidencia cargada exitosamente"
        │
        │ 8. Contador de archivos aumenta
        │   Mostrar: "1 archivo(s)" → "2 archivo(s)"


FASE 2: INSTRUCTOR REVISA Y FIRMA
═════════════════════════════════════════════════════════════════════

Instructor (ID: 1)
        │
        │ 1. Logout de colaborador
        │
        │ 2. Login como ID: 1
        ↓
    /sisgedi (SISGEDI Index)
        │
        │ 3. Click "Revisión de Entregables"
        ↓
    /sisgedi/instructor/revisar-entregables
        (InstructorDashboardController::revisarEntregables)
        │
        │ 4. Controller obtiene:
        │   - Approval::getPendingByInstructor(1)
        │   - Evidence::getByApproval(approval_id)
        │   - Signature::getActive(1)
        │
        │ 5. Ver lista de pendientes
        │   Colaborador: Carlos Testero
        │   Tarea: "Prueba: Informe de Actividades..."
        │   Estado: PENDIENTE (naranja)
        │
        │ 6. Haz clic en tarea para ver detalles
        ↓
    Detalle del Entregable (Columna derecha)
        │
        │ 7. Ver evidencias subidas
        │   - Links para descargar
        │
        │ 8. Click "Descargar para revisar"
        ↓
    GET /sisgedi/colaborador/evidences/{id}/download
        (EvidenceController::download)
        │
        ├─ Verificar permisos (¿Es el instructor?)
        ├─ Descargar archivo
        └─ Usuario descarga el PDF/Excel/etc.
        │
        │ 9. Revisor abre y verifica contenido
        │
        │ 10. Volver a la interfaz
        │
        │ 11. Click "Firmar y Aprobar" (verde)
        ↓
    Confirmación: "¿Deseas aprobar este entregable y firmarlo?"
        │
        │ 12. Click "Aceptar"
        ↓
    POST /sisgedi/instructor/approvals/{id}/approve
        (ApprovalController::approve)
        │
        ├─ Verificar sesión
        ├─ Verificar permisos (¿Es el instructor asignado?)
        ├─ Obtener firma activa: Signature::getActive(1)
        │  Retorna: Signature v2 (ID: 2)
        ├─ Llamar: approval->approve(signature_id)
        │  Actualiza:
        │  - status = 'aprobado'
        │  - signature_id = 2
        │  - reviewed_at = now()
        └─ Retornar mensaje de éxito
        │
        │ ✓ Mensaje: "Entregable aprobado y firmado exitosamente"


FASE 3: VERIFICAR FIRMA
═════════════════════════════════════════════════════════════════════

Instructor (aún logueado como ID: 1)
        │
        │ 1. Click "Mi Firma Digital"
        ↓
    /sisgedi/instructor/gestionar-firma
        (InstructorDashboardController::gestionarFirma)
        │
        │ 2. Controller obtiene:
        │   - Signature::getActive(1) → v2 (ID: 2)
        │   - Signature::getVersions(1) → [v1, v2, ...]
        │
        │ 3. Ver "Historial de Firmas"
        │   v2 (Vigente)
        │   Cargada: 15 Sep 2026
        │   ✓ 1 docs firmados  ← CONTADOR AUMENTÓ
        │
        │ 4. En la BD:
        │   SELECT COUNT(*) FROM approvals
        │   WHERE signature_id = 2 AND status = 'aprobado'
        │   RESULT: 1


ESTRUCTURA DE BASE DE DATOS
═════════════════════════════════════════════════════════════════════

approvals table
├── id (PK)
├── deliverable_id
├── deliverable_name: "Prueba: Informe de Actividades Semanal"
├── colaborador_id: 5 → "Carlos Testero"
├── instructor_id: 1 → "Dr. Juan López García"
├── status: 'pendiente' → 'aprobado'
├── file_path
├── file_type
├── signature_id: NULL → 2 (cuando aprobado)
├── submitted_at: 2026-09-15 14:00:00
├── reviewed_at: NULL → 2026-09-15 15:30:00
└── feedback: NULL (solo si rechazado)

evidences table
├── id (PK)
├── approval_id: 7 (FK → approvals)
├── colaborador_id: 5
├── file_path: 'storage/evidences/evidence_7_5_1234567890.pdf'
├── file_name: 'Informe_Semanal.pdf'
├── file_type: 'pdf'
├── file_size: 256 (KB)
├── description: 'Informe con resultados...'
└── uploaded_at: 2026-09-15 14:15:30

signatures table
├── id (PK): 2
├── instructor_id: 1
├── instructor_name: "Dr. Juan López García"
├── file_path: 'storage/signatures/...'
├── file_type: 'PNG'
├── version: 'v2'
├── is_active: true
└── uploaded_at: 2026-09-15 10:00:00


RUTAS DEL SISTEMA
═════════════════════════════════════════════════════════════════════

Colaborador:
  GET  /sisgedi/colaborador/mis-tareas
  POST /sisgedi/colaborador/evidences/{approval_id}/upload
  GET  /sisgedi/colaborador/evidences/{id}/download
  POST /sisgedi/colaborador/evidences/{id}/delete

Instructor:
  GET  /sisgedi/instructor/revisar-entregables
  POST /sisgedi/instructor/approvals/{id}/approve
  POST /sisgedi/instructor/approvals/{id}/reject
  GET  /sisgedi/instructor/gestionar-firma


MODELOS Y RELACIONES
═════════════════════════════════════════════════════════════════════

Approval (1 → N)
    ├── hasMany Evidence
    └── belongsTo Signature (when approved)

Evidence
    └── belongsTo Approval

Signature (1 → N)
    └── hasMany Approval (when used for approval)


VALIDACIONES Y PERMISOS
═════════════════════════════════════════════════════════════════════

Upload Evidencia:
  ✓ Usuario logueado?
  ✓ Es el colaborador asignado a la tarea?
  ✓ Archivo < 10MB?
  ✓ Tipo de archivo permitido?

Descargar Evidencia:
  ✓ Usuario logueado?
  ✓ Es el colaborador propietario O instructor asignado?

Aprobar Entregable:
  ✓ Usuario logueado?
  ✓ Es el instructor asignado?
  ✓ Tiene firma digital activa?

Rechazar Entregable:
  ✓ Usuario logueado?
  ✓ Es el instructor asignado?
  ✓ Feedback tiene 10-500 caracteres?


ESTADO VISUAL EN UI
═════════════════════════════════════════════════════════════════════

Mis Tareas (Colaborador)
┌─────────────────────────────────────────────────────────┐
│ Tarea │ Instructor │ Estado    │ Evidencias │ Acciones  │
├─────────────────────────────────────────────────────────┤
│       │            │ Pendiente │            │           │
│ Prueba│ Dr. Juan   │ (naranja) │ 2 archi(s) │ + Subir ✓ │
│       │            │           │ 📎 file1   │           │
│       │            │           │ 📎 file2   │           │
└─────────────────────────────────────────────────────────┘

       ↓ Después de aprobar

┌─────────────────────────────────────────────────────────┐
│ Tarea │ Instructor │ Estado    │ Evidencias │ Acciones  │
├─────────────────────────────────────────────────────────┤
│       │            │ Aprobado  │            │           │
│ Prueba│ Dr. Juan   │ (verde)   │ 2 archi(s) │ Completada│
│       │            │ ✓         │ 📎 file1   │           │
│       │            │           │ 📎 file2   │           │
└─────────────────────────────────────────────────────────┘


ARCHIVOS DEL SISTEMA
═════════════════════════════════════════════════════════════════════

storage/
└── evidences/
    ├── evidence_7_5_1234567890.pdf (Informe_Semanal.pdf)
    ├── evidence_7_5_1234567891.xlsx (Datos_Anexo.xlsx)
    └── ... (más evidencias)

public/
└── storage/
    └── evidences/
        └── (enlace simbólico a storage/evidences/)


FLUJO DE ARCHIVO
═════════════════════════════════════════════════════════════════════

Colaborador selecciona PDF
              │
              ↓
    POST request a servidor
              │
              ↓
    EvidenceController::upload()
    - Valida: tamaño, tipo
    - Crea nombre único: evidence_7_5_TIMESTAMP.pdf
              │
              ↓
    File::storeAs('evidences', 'evidence_7_5_...pdf', 'public')
    Guardado: storage/evidences/evidence_7_5_...pdf
              │
              ↓
    Crear registro en BD:
    {
      approval_id: 7,
      file_path: 'storage/evidences/...',
      file_name: 'Informe_Semanal.pdf',
      file_type: 'pdf',
      file_size: 256
    }
              │
              ↓
    ✓ Respuesta: "Evidencia cargada exitosamente"


TESTING
═════════════════════════════════════════════════════════════════════

Command: php artisan sisgedi:test-task
              │
              ├─ Verifica firma instructor (ID: 1)
              ├─ Crea Approval:
              │  - colaborador_id: 5
              │  - instructor_id: 1
              │  - status: 'pendiente'
              └─ Output: ID, credenciales, instrucciones

Usuario abre navegador
              │
              ├─ PASO 1: Login (5) → Upload evidencia
              ├─ PASO 2: Login (1) → Aprobar + firmar
              └─ PASO 3: Verificar firma → "1 docs firmados"

✓ TODAS LAS FUNCIONES PROBADAS
