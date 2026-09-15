<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SISGEDI\Entities\Documento;

class DocumentoController extends Controller
{
    // ── 1. PÁGINA PRINCIPAL — landing institucional ───────────────────
    public function index()
    {
        $totalDocumentos   = Documento::count();
        $documentosActivos = Documento::where('estado', 'Activo')->count();

        // Verificar si existe al menos una convocatoria abierta
        $hayConvocatoriaActiva = DB::table('convocatoria')
            ->where(function ($q) {
                $q->where('estado', 'abierta')->orWhere('estado', 'Abierta');
            })
            ->whereDate('fecha_cierre', '>=', now())
            ->exists();

        return view('sisgedi::index', compact(
            'totalDocumentos',
            'documentosActivos',
            'hayConvocatoriaActiva'
        ));
    }

    // ── 2. DASHBOARD (requiere sesión sisgedi_user) ───────────────────
    public function dashboard()
    {
        if (! session('sisgedi_user')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        $usuario = session('sisgedi_user');

        // ── Tarjetas de resumen ───────────────────────────────────────
        $totalUsuarios         = DB::table('users_sisgedi')->count();
        $totalSectores         = DB::table('sectors')->whereNull('deleted_at')->count();
        $convocatoriasAbiertas = DB::table('convocatoria')->where('estado', 'abierta')->count();

        // Documentos del listado maestro: activos = con archivo, pendientes = sin archivo
        $totalDocumentosMaestro  = DB::table('listado_maestro_documento')->count();
        $evidenciasPendientes    = DB::table('listado_maestro_documento')
            ->whereNull('archivo_url')
            ->orWhere('archivo_url', '')
            ->count();

        // Paz y Salvo: configuraciones totales vs las que tienen instructor asignado
        $pazYSalvoTotal     = DB::table('config_firma_rol_documento')->count();
        $pazYSalvoCompletos = DB::table('config_firma_rol_documento')
            ->whereNotNull('instructor_id')
            ->count();

        // ── Fase vigente: la ÚNICA fase activa o la más reciente ────────
        // Se muestra solo la fase actual, sin próximas fases en el dashboard
        $faseVigente = DB::table('fase')->orderBy('fase_id', 'desc')->first();
        $hayFases    = ! is_null($faseVigente);

        // Progreso de la fase vigente basado en convocatorias cerradas/finalizadas
        // respecto al total de convocatorias de esa fase
        $convocatoriasFase = $hayFases
            ? DB::table('convocatoria')->where('fase_id', $faseVigente->fase_id)->count()
            : 0;
        $convocatoriasCerradas = $hayFases
            ? DB::table('convocatoria')
                ->where('fase_id', $faseVigente->fase_id)
                ->whereIn('estado', ['cerrada', 'finalizada'])
                ->count()
            : 0;
        $progresoFase = $convocatoriasFase > 0
            ? round(($convocatoriasCerradas / $convocatoriasFase) * 100)
            : 0;

        // ── Actividad reciente: últimos usuarios creados en users_sisgedi ──
        // Unimos con roles_sisgedi para mostrar el rol
        $ultimosUsuarios = DB::table('users_sisgedi as u')
            ->leftJoin('roles_sisgedi as r', 'u.id_rol', '=', 'r.id_rol')
            ->select('u.nombre', 'u.correo', 'r.nombre as rol_nombre')
            ->orderBy('u.id_users', 'desc')
            ->take(3)
            ->get();

        // Últimas convocatorias creadas/modificadas
        $ultimasConvocatorias = DB::table('convocatoria')
            ->select('titulo', 'estado', 'fecha_apertura')
            ->orderBy('convocatoria_id', 'desc')
            ->take(2)
            ->get();

        // Construir feed de actividad reciente combinando ambas fuentes
        $actividadReciente = collect();

        foreach ($ultimosUsuarios as $u) {
            $actividadReciente->push([
                'icon'   => 'fas fa-user-plus',
                'color'  => '#3B82F6',
                'bg'     => '#EFF6FF',
                'texto'  => 'Usuario registrado: ' . ucwords(str_replace('.', ' ', $u->nombre))
                            . ($u->rol_nombre ? ' (' . $u->rol_nombre . ')' : ''),
                'tiempo' => 'Reciente',
            ]);
        }

        foreach ($ultimasConvocatorias as $c) {
            $actividadReciente->push([
                'icon'   => 'fas fa-bullhorn',
                'color'  => '#10B981',
                'bg'     => '#ECFDF5',
                'texto'  => 'Convocatoria: ' . ($c->titulo ?? 'Sin título')
                            . ' — Estado: ' . ucfirst($c->estado ?? 'desconocido'),
                'tiempo' => $c->fecha_apertura
                    ? 'Abierta desde ' . \Carbon\Carbon::parse($c->fecha_apertura)->format('d M Y')
                    : 'Sin fecha',
            ]);
        }

        // Si no hay actividad real, mostrar mensaje informativo
        if ($actividadReciente->isEmpty()) {
            $actividadReciente->push([
                'icon'   => 'fas fa-info-circle',
                'color'  => '#6B7280',
                'bg'     => '#F3F4F6',
                'texto'  => 'Aún no hay actividad registrada en el sistema.',
                'tiempo' => 'Ahora',
            ]);
        }

        // ── Documentos por sector (listado_maestro_documento) ────────
        $sectores = DB::table('sectors')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get(['id', 'name']);

        $documentosPorSector = [];
        foreach ($sectores as $sector) {
            $docs = DB::table('listado_maestro_documento')
                ->where('sector_id', $sector->id)
                ->get(['archivo_url', 'categoria']);

            $aprobadas  = $docs->whereNotNull('archivo_url')->where('archivo_url', '!=', '')->count();
            $pendientes = $docs->where(fn($d) => is_null($d->archivo_url) || $d->archivo_url === '')->count();
            // Sin tabla de estado explícita, usamos categoría "rechazado" si existe
            $rechazadas = $docs->where('categoria', 'rechazado')->count();
            $total      = $docs->count();

            $documentosPorSector[] = [
                'nombre'     => $sector->name,
                'aprobadas'  => $aprobadas,
                'rechazadas' => $rechazadas,
                'pendientes' => $pendientes,
                'total'      => $total,
            ];
        }

        return view('sisgedi::dashboard', compact(
            'usuario',
            'totalUsuarios',
            'totalSectores',
            'convocatoriasAbiertas',
            'evidenciasPendientes',
            'pazYSalvoCompletos',
            'pazYSalvoTotal',
            'faseVigente',
            'hayFases',
            'progresoFase',
            'actividadReciente',
            'documentosPorSector'
        ));
    }

    // ── 3. FORMULARIO DE CREACIÓN ─────────────────────────────────────
    public function create()
    {
        return view('sisgedi::create');
    }

    // ── 4. GUARDAR EN BASE DE DATOS ───────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'      => 'required|string|unique:documentos,codigo|max:50',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:Activo,Inactivo',
        ]);

        Documento::create($validated);

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento registrado con éxito!');
    }

    // ── 5. FORMULARIO DE EDICIÓN ──────────────────────────────────────
    public function edit($id)
    {
        $elemento = Documento::findOrFail($id);
        return view('sisgedi::edit', compact('elemento'));
    }

    // ── 6. ACTUALIZAR REGISTRO ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $elemento  = Documento::findOrFail($id);
        $validated = $request->validate([
            'codigo'      => 'required|string|max:50|unique:documentos,codigo,' . $elemento->id,
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:Activo,Inactivo',
        ]);

        $elemento->update($validated);

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento actualizado con éxito!');
    }

    // ── 7. ELIMINAR REGISTRO ──────────────────────────────────────────
    public function destroy($id)
    {
        $elemento = Documento::findOrFail($id);
        $elemento->delete();

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento eliminado correctamente!');
    }
}
