<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario con sesión activa en SISGEDI
     * (session('sisgedi_user'), poblada por AuthSisgediController).
     */
    public function show()
    {
        $sesion = session('sisgedi_user');

        if (! $sesion) {
            return redirect()->route('sisgedi.login')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        $usuario = DB::table('users_sisgedi')
            ->where('id_users', $sesion['id'])
            ->first();

        return view('sisgedi::perfil.show', [
            'sesion' => $sesion,
            'usuario' => $usuario,
        ]);
    }
}
