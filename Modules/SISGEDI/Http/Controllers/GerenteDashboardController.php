<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\Tarea;

class GerenteDashboardController extends Controller
{
    /**
     * Dashboard del Gerente Administrativo: seguimiento a la cascada de
     * tareas que ha generado hacia los Gestores de su area (RF-019).
     */
    public function index(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');
        $usuarioRol = $request->attributes->get('sisgedi_usuario_rol');

        $tareasQuery = Tarea::where('generador_usuario_id', session('sisgedi_user')['id'])
            ->where('fase_id', $fase->id);

        $totalTareas = (clone $tareasQuery)->count();

        $estados = [
            'planificada', 'asignada', 'en_desarrollo', 'enviada',
            'en_revision', 'aprobada', 'rechazada', 'vencida',
        ];

        $porEstado = [];
        foreach ($estados as $estado) {
            $porEstado[$estado] = (clone $tareasQuery)->where('estado', $estado)->count();
        }

        $ultimasTareas = (clone $tareasQuery)
            ->with(['sector', 'documentoGuia'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('sisgedi::gerente.dashboard', compact(
            'fase',
            'usuarioRol',
            'totalTareas',
            'porEstado',
            'ultimasTareas'
        ));
    }
}
