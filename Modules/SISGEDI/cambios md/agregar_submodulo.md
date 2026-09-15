# ¿Cómo agregar un nuevo submódulo o vista en el sistema?

Este documento explica paso a paso cómo crear una nueva vista (como por ejemplo "Plan Comercial" o "Portafolio de Productos") y conectarla correctamente al menú lateral y al sistema de rutas.

---

## 1. Crear el Controlador
Siempre es buena práctica que cada rol (o grupo de funciones) tenga su propio Controlador. Si vas a crear las vistas para el Gerente Comercial, debes crear o usar un controlador dedicado.

Ejemplo: Crear `GerenteComercialController.php` en `Modules/SISGEDI/Http/Controllers/`.

```php
<?php
namespace Modules\SISGEDI\Http\Controllers;

use Illuminate\Routing\Controller;

class GerenteComercialController extends Controller
{
    // Función para mostrar la vista del submódulo "Plan Comercial"
    public function planComercial()
    {
        return view('sisgedi::gerente_comercial.plan');
    }
}
```

---

## 2. Crear la Ruta en web.php
Para que la URL funcione (ej. `misitio.com/sisgedi/comercial/plan`), debes registrar la ruta en el archivo `Modules/SISGEDI/Routes/web.php`.

Ubica tu grupo de rutas y añade la nueva:

```php
use Modules\SISGEDI\Http\Controllers\GerenteComercialController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {
    
    // ... otras rutas ...

    // Rutas del Gerente Comercial
    Route::prefix('comercial')->name('comercial.')->group(function () {
        Route::get('/plan', [GerenteComercialController::class, 'planComercial'])->name('plan');
    });

});
```
*Con esto, el nombre de la ruta oficial será: `route('sisgedi.comercial.plan')`.*

---

## 3. Crear el Archivo de Vista (.blade.php)
Ahora necesitas crear el archivo visual que se mostrará en pantalla.

1. Ve a `Modules/SISGEDI/Resources/views/gerente_comercial/`
2. Crea un archivo llamado `plan.blade.php`.
3. Pega la estructura básica que extiende el sidebar:

```html
@extends('sisgedi::layouts.partials.sidebarcomercial')

@section('content')
<section class="py-6 bg-white rounded-xl shadow-sm border border-slate-100 p-8">
    <h1 class="text-2xl font-bold text-slate-800">Plan Comercial</h1>
    <p class="mt-2 text-slate-500">Aquí irá el contenido del módulo de plan comercial...</p>
</section>
@endsection
```

---

## 4. Conectar la vista al Menú Lateral
Por último, debes hacer que el botón del sidebar abra tu nueva vista.

1. Abre `Modules/SISGEDI/Resources/views/layouts/partials/sidebarcomercial.blade.php`.
2. Busca el enlace `<a href="">` correspondiente al Plan Comercial.
3. Actualiza el `href` usando el nombre de la ruta que creaste en el Paso 2.
4. (Opcional pero recomendado) Actualiza la clase dinámica para que el botón se "ilumine" cuando estés en esa página usando `request()->routeIs(...)`.

**Antes:**
```html
<a href="" class="flex items-center gap-3 px-4 py-2.5 ... text-slate-400">
    <i class="fas fa-clipboard-list w-5"></i>
    <span>Plan Comercial</span>
</a>
```

**Después:**
```html
<a href="{{ route('sisgedi.comercial.plan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('sisgedi.comercial.plan') ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
    <i class="fas fa-clipboard-list w-5 text-center"></i>
    <span>Plan Comercial</span>
</a>
```

¡Y listo! Ya creaste un submódulo 100% funcional y conectado a tu arquitectura modular.
