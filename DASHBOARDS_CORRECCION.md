# Corrección de Error: Internal Server Error

## Problema Encontrado
**Error:** `InvalidArgumentException - View [dashboard] not found`

Cuando el usuario iniciaba sesión, la aplicación intentaba renderizar una vista `sisgedi::dashboard` que no existía en la ubicación esperada por el layout master.

## Causas Raíz

1. Las nuevas vistas de dashboards usaban `@extends('sisgedi::layouts.app')` pero el layout correcto era `sisgedi::layouts.master`
2. El flujo de redirección después del login no distinguía entre roles diferentes
3. Faltaba la lógica de enrutamiento basada en roles en el controlador `DocumentoController`

## Soluciones Implementadas

### 1. ✅ Actualización de Vistas a Layout Correcto
Todas las 13 vistas creadas fueron actualizadas para usar el layout master:

**Cambio:** `@extends('sisgedi::layouts.app')` → `@extends('sisgedi::layouts.master')`

**Archivos actualizados:**
- `colaborador/dashboard.blade.php`
- `colaborador/mis-tareas.blade.php`
- `colaborador/bitacoras.blade.php`
- `colaborador/paz-y-salvo.blade.php`
- `colaborador/plan-innovacion.blade.php`
- `colaborador/convocatorias.blade.php`
- `colaborador/mis-evidencias.blade.php`
- `colaborador/doc-final-fase.blade.php`
- `instructor/dashboard.blade.php`
- `instructor/fichas-asignadas.blade.php`
- `instructor/detalle-colaborador.blade.php`
- `instructor/revisar-entregables.blade.php`
- `instructor/gestionar-firma.blade.php`

### 2. ✅ Lógica de Enrutamiento Basada en Roles
Se modificó el método `dashboard()` del `DocumentoController` para:

1. Verificar que el usuario esté autenticado en SISGEDI
2. Obtener el rol del usuario desde la sesión
3. Validar que el rol no esté vacío
4. Redirigir según el tipo de rol:

```php
// Colaborador → Dashboard de Colaborador (HU-001 a HU-009)
if ($rol === 'colaborador') {
    return redirect()->route('sisgedi.colaborador.dashboard');
}

// Gestor, Líder, Gerente → Dashboard de Instructor (HU-010 a HU-013)
if (str_contains($rol, 'gestor') || str_contains($rol, 'líder') || str_contains($rol, 'gerente')) {
    return redirect()->route('sisgedi.instructor.dashboard');
}
```

### 3. ✅ Limpieza de Caché
Se ejecutaron los comandos para limpiar caché de Laravel:
- `php artisan config:clear`
- `php artisan view:clear`

## Mapeo de Roles en Base de Datos

Los roles disponibles en la tabla `roles_sisgedi` son:

### Roles de Colaborador
- **Colaborador** (ID: 25) → Dashboard de Colaborador ✅

### Roles de Instructor/Supervisor
- Gerente General (ID: 1)
- Gerente Administrativo (ID: 2)
- Gerente de Producción (ID: 3)
- Gerente Comercial (ID: 4)
- Gestor Talento Humano (ID: 5)
- Gestor Contabilidad y Finanzas (ID: 6)
- Gestor ASIG (ID: 7)
- Gestor de Investigación (ID: 8)
- Gestor Innovación y Prototipado (ID: 9)
- Gestor Fábrica de Software (ID: 10)
- Gestor Área Agrícola (ID: 11)
- Gestor Área Pecuaria (ID: 12)
- Gestor Área Agroindustrial (ID: 13)
- Gestor Área Ambiental (ID: 14)
- Gestor Mercadeo (ID: 15)
- Gestor de Educación Bilingüe (ID: 16)
- Líder Equipo Talento (ID: 17)
- Líder Equipo SIG (ID: 18)
- Líder Fábrica de Software (ID: 19)
- Líder Equipo Agrícola (ID: 20)
- Líder Equipo Pecuaria (ID: 21)
- Líder Equipo Agroindustrial (ID: 22)
- Líder Equipo Ambiental (ID: 23)
- Líder Equipo Mercadeo (ID: 24)

### Todos redirigen a Dashboard de Instructor ✅

## Flujo de Autenticación Actualizado

```
1. Usuario accede a /sisgedi
   ↓
2. Usuario introduce credenciales (nickname/email + password)
   ↓
3. AuthSisgediController valida contra users_sisgedi
   ↓
4. Se crea sesión 'sisgedi_user' con datos incluyendo rol
   ↓
5. Redirecciona a route('sisgedi.dashboard')
   ↓
6. DocumentoController::dashboard() verifica el rol
   ↓
7. Si es "Colaborador" → redirige a sisgedi.colaborador.dashboard
   Si es "Gestor/Líder/Gerente" → redirige a sisgedi.instructor.dashboard
   ↓
8. Se renderiza la vista del dashboard correspondiente con layout::master
   ↓
9. Usuario ve su dashboard personalizado
```

## Pruebas Realizadas

✅ Verificación de roles en base de datos
✅ Actualización de todas las vistas a layout master
✅ Lógica de redirección basada en roles implementada
✅ Caché de Laravel limpiado
✅ Rutas definidas en Routes/web.php

## Próximos Pasos

1. **Prueba de Login:** Inicia sesión con un usuario de rol "Colaborador" y verifica que redirija a `/sisgedi/colaborador/dashboard`
2. **Prueba de Login (Instructor):** Inicia sesión con un usuario de rol "Gestor" o "Líder" y verifica que redirija a `/sisgedi/instructor/dashboard`
3. **Integración Backend:** Conectar las vistas con la lógica de negocio real para cada dashboard

## Archivos Modificados

```
Modules/SISGEDI/
├── Http/Controllers/
│   └── DocumentoController.php (✅ actualizado - método dashboard())
├── Resources/views/
│   ├── colaborador/ (✅ 8 archivos - layout actualizado)
│   └── instructor/ (✅ 5 archivos - layout actualizado)
└── Routes/web.php (✅ rutas agregadas correctamente)
```

---

**Estado:** ✅ LISTO PARA PRUEBA
**Fecha:** 15 de Septiembre de 2026
**Versión:** SISGEDI v7
