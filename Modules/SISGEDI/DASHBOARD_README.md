# Dashboards SISGEDI - Guía de Implementación

## Estructura General

El módulo SISGEDI cuenta con dos dashboards principales:

1. **Dashboard del Gestor** (`/sisgedi/gestor/dashboard`)
2. **Dashboard del Líder** (`/sisgedi/lider/dashboard`)

### Dashboard del Gestor

Acceso dinámico basado en el tipo de gestor registrado en `users_sisgedi`. Detecta automáticamente el tipo de gestor del rol (e.g., `gestor.talento`, `gestor.contabilidad`, etc.).

**Tipos de gestores soportados:**
- Talento
- Contabilidad
- ASIG
- Investigación
- Innovación
- Fábrica
- Agrícola
- Pecuaria
- Agroindustrial
- Ambiental
- Mercadeo
- Bilingüe

**Módulos del Dashboard de Gestor (sin funcionalidad):**
1. Tareas Activas
2. Líderes a Cargo
3. Fase Vigente
4. Evidencias en Revisión
5. Evidencias Rechazadas
6. Bitácoras al Día
7. Actividad Reciente
8. Progreso de mis Tareas
9. Evidencias por Líder

### Dashboard del Líder

Acceso para usuarios con rol de líder. Muestra información personalizada según el usuario.

**Módulos del Dashboard de Líder (sin funcionalidad):**
1. Mi Equipo
2. Tareas Asignadas
3. Tareas Completadas
4. Evidencias Pendientes
5. Evidencias Aprobadas
6. Evidencias Rechazadas
7. Mi Progreso
8. Actividad Reciente
9. Tareas del Equipo
10. Mi Bitácora

## Estructura de Archivos

```
Modules/SISGEDI/
├── config/
│   └── gestores.php                    # Configuración de tipos de gestor
├── Http/Controllers/
│   ├── SISGEDIController.php           # Controladores de dashboards
│   └── ...
├── resources/views/dashboards/
│   ├── gestor.blade.php                # Vista del dashboard gestor
│   └── lider.blade.php                 # Vista del dashboard líder
└── routes/
    └── web.php                         # Rutas de los dashboards
```

## Flujo de Autenticación

1. Usuario ingresa credenciales en `sisgedi::index`
2. `AuthSisgediController::login()` valida contra `users_sisgedi`
3. Se crea sesión en `session('sisgedi_user')`
4. Usuario redirigido a `sisgedi.dashboard` (DocumentoController)
5. Desde ahí puede acceder a:
   - `/sisgedi/gestor/dashboard` (si es gestor)
   - `/sisgedi/lider/dashboard` (si es líder)

## Variables Disponibles en las Vistas

### Dashboard del Gestor

```blade
{{ $user['nombre'] }}         {{-- Nombre del usuario --}}
{{ $user['correo'] }}         {{-- Correo del usuario --}}
{{ $user['rol'] }}            {{-- Rol completo (ej: "gestor.talento") --}}
{{ $tipoGestor }}             {{-- Tipo de gestor (ej: "talento") --}}
{{ $configGestor['nombre'] }} {{-- Nombre del gestor (ej: "Gestor de Talento") --}}
{{ $configGestor['color'] }}  {{-- Color para el gestor --}}
```

### Dashboard del Líder

```blade
{{ $user['nombre'] }}  {{-- Nombre del usuario --}}
{{ $user['correo'] }}  {{-- Correo del usuario --}}
{{ $rol }}             {{-- Rol completo --}}
```

## Configuración de Gestores

El archivo `config/gestores.php` define:

- **nombre**: Nombre descriptivo del tipo de gestor
- **color**: Color de Tailwind para la interfaz
- **modulos**: Lista de módulos disponibles en el dashboard

Ejemplo:

```php
'talento' => [
    'nombre' => 'Gestor de Talento',
    'color' => 'blue',
    'modulos' => [
        'tareas_activas',
        'lideres_cargo',
        // ...
    ]
]
```

## Próximos Pasos para Desarrollar Módulos

Cada módulo sin funcionalidad actualmente mostrará:
- Un contador con valor "0"
- Un ícono representativo
- Un nombre descriptivo

Para agregar funcionalidad a un módulo:

1. **Crear un controlador** si es necesario
2. **Agregar lógica de consulta** en el controlador del dashboard
3. **Pasar datos a la vista**
4. **Actualizar la vista** para mostrar datos dinámicos
5. **Crear rutas** si hay acciones específicas

## Notas de Seguridad

- La autenticación de SISGEDI es **independiente** de la autenticación de Laravel
- Las contraseñas se almacenan en **texto plano** en `users_sisgedi` (riesgo de seguridad)
- La sesión se almacena con clave `sisgedi_user`
- Se recomienda validar `session('sisgedi_user')` en todos los dashboards

## Testing

Para probar los dashboards:

1. Acceder a `/sisgedi/` (página de login)
2. Usar credenciales de un usuario con rol `gestor.*` o `lider`
3. Se redirigirá al dashboard correspondiente

### Rutas de Acceso Directo

- **Dashboard Gestor**: `/sisgedi/gestor/dashboard`
- **Dashboard Líder**: `/sisgedi/lider/dashboard`

## Historial de Cambios

### v1.0 - Creación Inicial
- Dashboard del Gestor dinámico por tipo
- Dashboard del Líder
- Configuración centralizada de tipos de gestor
- Todos los módulos como placeholders sin funcionalidad
