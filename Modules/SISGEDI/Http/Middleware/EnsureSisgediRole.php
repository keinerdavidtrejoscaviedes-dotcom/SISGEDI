<?php

namespace Modules\SISGEDI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SISGEDI\Entities\Fase;
use Modules\SISGEDI\Entities\UsuarioRol;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario autenticado tenga, en la fase vigente, una
 * asignacion de rol SISGEDI activa (tabla sisgedi_usuario_roles).
 *
 * Uso en rutas: ->middleware('rol.sisgedi:GerenteAdministrativo')
 */
class EnsureSisgediRole
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Debes iniciar sesión para acceder a SISGEDI.');
        }

        $fase = Fase::vigente();

        if (! $fase) {
            abort(403, 'No hay una fase activa configurada en SISGEDI.');
        }

        $usuarioRol = UsuarioRol::activaPara(Auth::id(), $rol, $fase->id);

        if (! $usuarioRol) {
            abort(403, 'No tienes el rol de '.$rol.' activo en la fase vigente de SISGEDI.');
        }

        // Se deja disponible en el request para los controladores.
        $request->attributes->set('sisgedi_usuario_rol', $usuarioRol);
        $request->attributes->set('sisgedi_fase', $fase);

        return $next($request);
    }
}
