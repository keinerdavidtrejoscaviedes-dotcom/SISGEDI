<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Muestra la vista de inicio de sesión del ERP.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            $redirect = $request->query('redirect', '');
            if (!empty($redirect)) {
                return redirect($redirect)->with('info', 'Ya has iniciado sesión como ' . Auth::user()->full_name);
            }

            // Si ya es el gestor de evidencias, mandarlo a su panel
            if (Auth::id() === 124) {
                return redirect()->route('evidencias.index')
                    ->with('info', 'Ya has iniciado sesión como ' . Auth::user()->full_name);
            }

            // Si ya es yina, mandarla a su dashboard
            if (Auth::id() === 125) {
                return redirect()->route('sisgedi.dashboard')
                    ->with('info', 'Ya has iniciado sesión como ' . Auth::user()->full_name);
            }

            return redirect(route('direccion.welcome'))
                ->with('info', 'Ya has iniciado sesión como ' . Auth::user()->full_name);
        }

        $redirect = $request->query('redirect', '');
        return view('login', compact('redirect'));
    }

    /**
     * Procesa la autenticación del usuario en el ERP.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Debes ingresar tu correo institucional o usuario.',
            'password.required' => 'Debes ingresar tu contraseña.',
        ]);

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Buscar al usuario por correo o por nickname
        $user = User::where('email', $loginInput)
            ->orWhere('nickname', $loginInput)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Redirigir al panel exclusivo del gestor de evidencias.
            // Se hace ANTES de verificar 'redirect' externo y se limpia
            // la URL intended para que redirect()->intended() no la sobrescriba.
            if ($user->id === 124) {
                $request->session()->forget('url.intended');
                return redirect()->route('evidencias.index')
                    ->with('success', '¡Bienvenido(a) al Panel de Evidencias, ' . $user->full_name . '!');
            }

            // Redirigir a yina (Administradora SISGEDI) a su dashboard.
            if ($user->id === 125) {
                $request->session()->forget('url.intended');
                // Buscar el id_users de yina en users_sisgedi para poder crear convocatorias
                $yinaSisgedi = \Illuminate\Support\Facades\DB::table('users_sisgedi')
                    ->where('nombre', 'yina')
                    ->first();
                // Inyectar sesión de SISGEDI para que el dashboard la reconozca
                $request->session()->put('sisgedi_user', [
                    'id'         => $user->id,
                    'id_sisgedi' => $yinaSisgedi ? (int) $yinaSisgedi->id_users : null,
                    'nombre'     => $user->nickname,
                    'correo'     => $user->email,
                    'rol'        => 'Administrador',
                    'id_rol'     => 26,
                    'es_admin'   => true,
                ]);
                return redirect()->route('sisgedi.dashboard')
                    ->with('success', '¡Bienvenida al Dashboard, ' . $user->full_name . '!');
            }

            $redirectUrl = $request->input('redirect');
            if (!empty($redirectUrl) && (str_starts_with($redirectUrl, '/') || str_starts_with($redirectUrl, url('/')))) {
                return redirect($redirectUrl)->with('success', '¡Bienvenido(a) a SENA Empresa, ' . $user->full_name . '!');
            }

            return redirect()->intended(route('direccion.welcome'))->with('success', '¡Bienvenido(a) a SENA Empresa, ' . $user->full_name . '!');
        }

        return back()
            ->withInput($request->only('email', 'remember', 'redirect'))
            ->withErrors([
                'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
            ]);
    }

    /**
     * Cierra la sesión activa del usuario.
     */
    public function logout(Request $request)
    {
        $userName = Auth::check() ? Auth::user()->full_name : 'Usuario';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirectUrl = $request->input('redirect', $request->query('redirect', ''));
        if (!empty($redirectUrl) && (str_starts_with($redirectUrl, '/') || str_starts_with($redirectUrl, url('/')))) {
            return redirect($redirectUrl)->with('info', 'Has cerrado sesión exitosamente.');
        }

        return redirect()->route('direccion.welcome')->with('info', 'Has cerrado sesión exitosamente. ¡Hasta pronto, ' . $userName . '!');
    }
}
