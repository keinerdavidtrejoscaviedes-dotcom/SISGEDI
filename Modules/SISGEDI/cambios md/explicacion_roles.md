# Explicación del Enrutamiento Dinámico por Roles (Actualizado)

Este documento explica la nueva arquitectura de enrutamiento que aplicamos (basada en tu excelente sugerencia de centralizar el Dashboard) y funciona como guía para que agregues el resto de roles en el futuro.

## 1. ¿Cómo funciona la arquitectura actual?

Para mantener tu sistema limpio y seguir las mejores prácticas de Laravel, decidimos **no** crear decenas de rutas en `web.php` ni llenar tu controlador de inicio de sesión de código innecesario.

La lógica actual funciona así:
1. Cuando un usuario inicia sesión, el sistema *siempre* lo envía a una única URL: `/dashboard`.
2. Esa ruta `/dashboard` ejecuta la función `dashboard()` dentro del archivo `DocumentoController.php`.
3. ¡Ese controlador actúa como cerebro! Pregunta: *"¿Qué número de rol tiene el usuario que acaba de entrar?"*. Dependiendo de la respuesta, dibuja en pantalla una plantilla visual distinta.

---

## 2. ¿Cómo agregar la vista de otro rol fácilmente?

Si en el futuro quieres añadir un panel exclusivo para el **Gerente General** (que según tu base de datos tiene el `id_rol` = 1), el proceso es mucho más simple. Solo necesitas **dos pasos**:

### PASO A: Crea el archivo de la vista
1. Ve a `Modules/SISGEDI/Resources/views/`
2. Crea una nueva carpeta llamada, por ejemplo, `gerente_general`.
3. Dentro, crea tu archivo `dashboard.blade.php` y arma ahí todo tu diseño HTML.

### PASO B: Añade la condición en el Controlador
1. Abre el archivo `Modules/SISGEDI/Http/Controllers/DocumentoController.php`.
2. Busca la función `public function dashboard()`.
3. Justo debajo de donde configuramos el gerente comercial, agrega un `elseif` para el rol 1:

```php
// Obtenemos al usuario conectado
$usuario = session('sisgedi_user');

// -- AQUÍ ESTÁN LAS VALIDACIONES POR ROL --

if ($usuario['id_rol'] == 4) {
    // Si es 4, mostramos el panel del Gerente Comercial
    return view('sisgedi::gerente_comercial.dashboard');
} 
elseif ($usuario['id_rol'] == 1) {
    // NUEVO: Si es 1, mostramos el panel del Gerente General
    return view('sisgedi::gerente_general.dashboard');
}

// ... 
// (El resto del código hacia abajo sigue igual, es el panel general)
```

**¡Y listo!**
- No necesitas tocar el archivo `web.php`.
- No necesitas modificar el controlador del login.
- Toda la lógica se maneja de forma inteligente desde una sola función.
