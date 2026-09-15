<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SISGEDI\Entities\Fase;
use Modules\SISGEDI\Entities\PlanTrabajo;
use Modules\SISGEDI\Entities\UsuarioRol;

class PlanTrabajoController extends Controller
{
    /**
     * RN-028: el plan de trabajo general de la fase es visible para todos
     * los roles de la fase (no solo para el Gerente Administrativo que lo
     * publica). Cualquier usuario con un rol SISGEDI activo en la fase
     * vigente puede consultarlo.
     */
    public function show()
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Debes iniciar sesión para consultar el plan de trabajo de la fase.');
        }

        $fase = Fase::vigente();

        if (! $fase) {
            abort(403, 'No hay una fase activa configurada en SISGEDI.');
        }

        $tieneRolActivo = UsuarioRol::where('user_id', Auth::id())
            ->activo()
            ->where('fase_id', $fase->id)
            ->exists();

        if (! $tieneRolActivo) {
            abort(403, 'No tienes un rol activo en la fase vigente de SISGEDI.');
        }

        $plan = PlanTrabajo::where('fase_id', $fase->id)
            ->where('nivel', 'general')
            ->with('hitos', 'autor')
            ->first();

        return view('sisgedi::plan-trabajo.show', compact('fase', 'plan'));
    }
}
