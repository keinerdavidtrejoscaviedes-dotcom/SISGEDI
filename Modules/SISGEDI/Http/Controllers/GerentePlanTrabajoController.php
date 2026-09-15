<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISGEDI\Entities\HitoPlanTrabajo;
use Modules\SISGEDI\Entities\PlanTrabajo;

class GerentePlanTrabajoController extends Controller
{
    /**
     * RF-053 / RN-028: el Gerente Administrativo elabora el plan de trabajo
     * general de la fase (fechas de entrega, reuniones, hitos). Solo existe
     * un plan de nivel "general" por fase.
     */
    public function edit(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');

        $plan = PlanTrabajo::where('fase_id', $fase->id)
            ->where('nivel', 'general')
            ->with('hitos')
            ->first();

        return view('sisgedi::gerente.plan-trabajo.edit', compact('fase', 'plan'));
    }

    /**
     * Crea o actualiza el plan general de la fase vigente (RN-028) junto con
     * sus hitos. Los hitos se reemplazan por completo en cada guardado.
     */
    public function save(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');

        $validated = $request->validate([
            'descripcion' => 'required|string',
            'hitos' => 'required|array|min:1',
            'hitos.*.titulo' => 'required|string|max:255',
            'hitos.*.fecha' => 'required|date',
            'hitos.*.tipo' => 'required|in:entrega,reunion,hito',
        ], [
            'hitos.required' => 'El plan de trabajo debe incluir al menos un hito, entrega o reunión.',
            'hitos.min' => 'El plan de trabajo debe incluir al menos un hito, entrega o reunión.',
        ]);

        DB::transaction(function () use ($validated, $fase) {
            $plan = PlanTrabajo::updateOrCreate(
                ['fase_id' => $fase->id, 'nivel' => 'general', 'user_id' => Auth::id()],
                ['fecha_publicacion' => now(), 'descripcion' => $validated['descripcion']]
            );

            $plan->hitos()->delete();

            foreach ($validated['hitos'] as $hito) {
                HitoPlanTrabajo::create([
                    'plan_trabajo_id' => $plan->id,
                    'titulo' => $hito['titulo'],
                    'fecha' => $hito['fecha'],
                    'tipo' => $hito['tipo'],
                ]);
            }
        });

        return redirect()->route('sisgedi.gerente.plan-trabajo.edit')
            ->with('success', 'Plan de trabajo de la fase publicado. Ya es visible para todos los roles.');
    }
}
