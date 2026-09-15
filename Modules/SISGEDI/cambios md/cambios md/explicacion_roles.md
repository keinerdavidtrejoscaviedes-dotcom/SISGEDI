# Explicación del Enrutamiento Dinámico por Roles

Este documento explica por qué se agregaron las líneas recientes de código y funciona como guía para que puedas agregar las vistas del resto de los roles fácilmente en el futuro.

## 1. ¿Por qué se agregaron las nuevas líneas?

Anteriormente, cuando cualquier usuario iniciaba sesión, el controlador (`AuthSisgediController.php`) enviaba a todo el mundo ciegamente al mismo panel (`sisgedi.dashboard`). 

Para lograr que cada rol tenga su propia pantalla (como la del **Gerente Comercial**), tuvimos que hacer tres cosas clave:

1. **Crear su propia ruta:** En `web.php`, agregamos una URL específica (`/gerente-comercial`) que le dice al sistema qué archivo visual cargar.
2. **Crear su vista (Blade):** Creamos una carpeta exclusiva (`gerente_comercial/dashboard.blade.php`) para guardar su diseño, manteniéndolo organizado e independiente del resto de roles.
3. **Programar el redireccionamiento (Switch):** En `AuthSisgediController.php`, implementamos un condicional `switch` que funciona como un policía de tránsito: evalúa el número de rol (`$usuario->id_rol`) y te envía a la ruta que te corresponde.

---

## 2. ¿Cómo agregar la vista de otro rol fácilmente?

Si quieres añadir la vista para el **Gerente General** (que según tu base de datos tiene el `id_rol` = 1), solo tienes que seguir estos 3 pasos exactos copiando y pegando el código:

### PASO A: Crea el archivo de la vista
1. En tu editor, ve a `Modules/SISGEDI/Resources/views/`
2. Crea una nueva carpeta llamada, por ejemplo, `gerente_general`
3. Dentro, crea el archivo `dashboard.blade.php` y ponle tu diseño HTML.

### PASO B: Registra la ruta
Abre tu archivo `Modules/SISGEDI/Routes/web.php` y pega lo siguiente debajo de las rutas de vistas específicas:

```php
Route::get('/gerente-general', function () {
    return view('sisgedi::gerente_general.dashboard');
})->name('gerente_general.dashboard');
```

### PASO C: Añádelo al Controlador
Abre tu archivo `Modules/SISGEDI/Http/Controllers/AuthSisgediController.php`, busca el `switch` que está dentro de la función `login()`, y simplemente añade el nuevo "caso" (case) para el rol 1:

```php
switch ($usuario->id_rol) {
    case 1: // Gerente General (NUEVO)
        return redirect()->route('sisgedi.gerente_general.dashboard')
            ->with('success', '¡Bienvenido Gerente General, ' . $usuario->nombre . '!');

    case 4: // Gerente Comercial (EL QUE YA TENÍAMOS)
        return redirect()->route('sisgedi.gerente_comercial.dashboard')
            ->with('success', '¡Bienvenido Gerente Comercial, ' . $usuario->nombre . '!');
            
    default:
        return redirect()->route('sisgedi.dashboard')
            ->with('success', '¡Bienvenido, ' . $usuario->nombre . '!');
}
```

¡Eso es todo! De esta misma manera, copiando estos 3 bloques, puedes agregar infinitos roles sin miedo a romper el sistema.
