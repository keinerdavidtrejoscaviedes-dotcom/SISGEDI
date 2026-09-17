<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\SISGEDI\Entities\Fase;
use Modules\SISGEDI\Entities\UsuarioRol;

class AuthSisgediController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión (Vista).
     */
    public function showLoginForm()
    {
        return redirect()->route('sisgedi.index');
    }

    /**
     * Procesa el inicio de sesión contra users_sisgedi.
     * La tabla almacena la contraseña en texto plano (varchar 50).
     */
    public function login(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string',
            'password' => 'required|string',
        ], [
            'nickname.required' => 'Debes ingresar tu correo institucional o usuario.',
            'password.required' => 'Debes ingresar tu contraseña.',
        ]);

        $credencial = trim($request->nickname);
        $password   = $request->password;

        // Buscar por nombre de usuario O por correo
        $usuario = DB::table('users_sisgedi')
            ->where('nombre', $credencial)
            ->orWhere('correo', $credencial)
            ->first();

        if (! $usuario) {
            return back()
                ->withInput($request->only('nickname'))
                ->withErrors(['nickname' => 'El usuario o correo no está registrado en SISGEDI.']);
        }

        // Comparación directa (la tabla usa varchar sin hash)
        if ($usuario->contraseña !== $password) {
            return back()
                ->withInput($request->only('nickname'))
                ->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        // Obtener el nombre del rol
        $rol = DB::table('roles_sisgedi')
            ->where('id_rol', $usuario->id_rol)
            ->value('nombre');

        // Guardar sesión propia de SISGEDI (compatibilidad con lo ya existente)
        Session::put('sisgedi_user', [
            'id'     => $usuario->id_users,
            'nombre' => $usuario->nombre,
            'correo' => $usuario->correo,
            'rol'    => $rol ?? 'Sin rol',
            'id_rol' => $usuario->id_rol,
        ]);

        // Redireccionar al dashboard correspondiente según el rol
        return $this->redirectPorRol($usuario->id_rol, $rol, $usuario->nombre);
    }

    /**
     * Redirecciona al dashboard correspondiente según el rol del usuario en SISGEDI.
     * Público para que otros controladores (p. ej. al detectar una sesión ya
     * activa) puedan reutilizar la misma lógica sin duplicarla.
     */
    public function redirectPorRol($idRol, $rolNombre, $usuarioNombre)
    {
        $rolLower = mb_strtolower(trim($rolNombre ?? ''));

        // 1. Aprendiz (id_rol = 27) -> Panel del Aprendiz (Convocatorias y postulaciones)
        if ($idRol == 27 || $rolLower === 'aprendiz') {
            return redirect()->route('sisgedi.aprendiz.panel')
                ->with('success', '¡Bienvenido(a), ' . $usuarioNombre . '!');
        }

        // 2. Gerente Comercial (id_rol = 4) -> Dashboard Comercial (Portafolio de productos)
        if ($idRol == 4 || str_contains($rolLower, 'comercial')) {
            return redirect()->route('sisgedi.comercial.dashboard')
                ->with('success', '¡Bienvenido(a), ' . $usuarioNombre . '!');
        }

        // 3. Gerente Administrativo (id_rol = 2) -> Panel de cascada de tareas (RF-019)
        if ($idRol == 2 || str_contains($rolLower, 'administrativo')) {
            return redirect()->route('sisgedi.gerente.dashboard')
                ->with('success', '¡Bienvenido(a), ' . $usuarioNombre . '!');
        }

        // 4. Administrador (26), Gerente General (1), Gerente de Producción (3),
        //    Gestores, Líderes y Colaborador -> Dashboard Principal
        return redirect()->route('sisgedi.dashboard')
            ->with('success', '¡Bienvenido(a), ' . $usuarioNombre . '!');
    }

    /**
     * Cierra la sesión de SISGEDI.
     */
    public function logout(Request $request)
    {
        Session::forget('sisgedi_user');

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('sisgedi.index')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
