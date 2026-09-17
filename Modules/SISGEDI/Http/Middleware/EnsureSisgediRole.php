<?php

namespace Modules\SISGEDI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\Cargo;
use Modules\SISGEDI\Entities\Fase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario de la sesión SISGEDI (`session('sisgedi_user')`,
 * poblada por AuthSisgediController a partir de `users_sisgedi` +
 * `roles_sisgedi`) tenga el rol indicado, y que exista una fase vigente
 * en la tabla `fase` (RN-010).
 *
 * Uso en rutas: ->middleware('rol.sisgedi:GerenteAdministrativo')
 */
class EnsureSisgediRole
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        $sesion = session('sisgedi_user');

        if (! $sesion) {
            return redirect()->route('sisgedi.login')
                ->with('info', 'Debes iniciar sesión para acceder a SISGEDI.');
        }

        $rolSesion = mb_strtolower(str_replace(' ', '', $sesion['rol'] ?? ''));
        $rolEsperado = mb_strtolower($rol);

        if ($rolSesion !== $rolEsperado) {
            abort(403, 'No tienes el rol de '.$rol.' asignado en SISGEDI.');
        }

        $fase = Fase::vigente();

        if (! $fase) {
            abort(403, 'No hay una fase activa configurada en SISGEDI.');
        }

        // "GerenteAdministrativo" -> "GERENTE_ADMINISTRATIVO" (enum sisgedi_cargos.tipo_cargo)
        $tipoCargo = strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $rol));
        $cargo = Cargo::with('gerencia')->where('tipo_cargo', $tipoCargo)->first();

        // Se deja disponible en el request para los controladores, en el
        // mismo formato que esperaban las vistas ya construidas.
        $usuarioRol = (object) [
            'id_users' => $sesion['id'],
            'rol' => $sesion['rol'],
            'cargo' => $cargo,
        ];

        $request->attributes->set('sisgedi_usuario_rol', $usuarioRol);
        $request->attributes->set('sisgedi_fase', $fase);

        return $next($request);
    }
}
