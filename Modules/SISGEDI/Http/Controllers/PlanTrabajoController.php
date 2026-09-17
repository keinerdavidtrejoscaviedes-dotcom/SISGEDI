<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\SISGEDI\Entities\Fase;
use Modules\SISGEDI\Entities\PlanTrabajo;

class PlanTrabajoController extends Controller
{
    /**
     * RN-028: el plan de trabajo general de la fase es visible para todos
     * los roles de la fase (no solo para el Gerente Administrativo que lo
     * publica). Cualquier usuario con sesión activa en SISGEDI puede
     * consultarlo.
     */
    public function show()
    {
        if (! session('sisgedi_user')) {
            return redirect()->route('sisgedi.login')
                ->with('info', 'Debes iniciar sesión para consultar el plan de trabajo de la fase.');
        }

        $fase = Fase::vigente();

        if (! $fase) {
            abort(403, 'No hay una fase activa configurada en SISGEDI.');
        }

        $plan = PlanTrabajo::where('fase_id', $fase->id)
            ->where('nivel', 'general')
            ->with('hitos', 'autor')
            ->first();

        return view('sisgedi::plan-trabajo.show', compact('fase', 'plan'));
    }
}
