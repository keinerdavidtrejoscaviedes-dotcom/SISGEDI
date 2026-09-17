<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;
use Modules\SISGEDI\Http\Controllers\ConvocatoriaController;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\EntrevistaController;
use Modules\SISGEDI\Http\Controllers\FaseController;
use Modules\SISGEDI\Http\Controllers\GerenteComercialController;
use Modules\SISGEDI\Http\Controllers\GerenteDashboardController;
use Modules\SISGEDI\Http\Controllers\GerentePlanTrabajoController;
use Modules\SISGEDI\Http\Controllers\GerenteTareaController;
use Modules\SISGEDI\Http\Controllers\PerfilController;
use Modules\SISGEDI\Http\Controllers\PlanTrabajoController;
use Modules\SISGEDI\Http\Controllers\PostulacionController;
use Modules\SISGEDI\Http\Controllers\SISGEDIController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {

    Route::get('/assets/gestor-dashboard.css', function () {
        return response()->file(
            module_path('SISGEDI', 'Resources/assets/css/gestor-dashboard.css'),
            ['Content-Type' => 'text/css']
        );
    })->name('assets.gestor_css');

    Route::prefix('comercial')->name('comercial.')->group(function () {
        Route::get('/producto', [GerenteComercialController::class, 'portafolioProducto'])->name('producto');
        Route::post('/producto', [GerenteComercialController::class, 'storeProducto'])->name('producto.store');
        Route::put('/producto/{id}', [GerenteComercialController::class, 'updateProducto'])->name('producto.update');
        Route::delete('/producto/{id}', [GerenteComercialController::class, 'destroyProducto'])->name('producto.destroy');
        Route::patch('/producto/{id}/activar', [GerenteComercialController::class, 'activarProducto'])->name('producto.activar');
        Route::get('/dashboard', [GerenteComercialController::class, 'dasboardComercial'])->name('dashboard');
    });

    // ── Públicas ──────────────────────────────────────────────────
    Route::get('/',        [DocumentoController::class,  'index'])->name('index');
    Route::get('/login',   [AuthSisgediController::class, 'showLoginForm'])->name('login');
    Route::post('/login',  [AuthSisgediController::class, 'login'])->name('login.post');
    Route::match(['get', 'post'], '/logout', [AuthSisgediController::class, 'logout'])->name('logout');

    // ── Dashboard (admin / gestor) ────────────────────────────────
    Route::get('/dashboard', [DocumentoController::class, 'dashboard'])->name('dashboard');
    Route::get('/gestor/dashboard', [SISGEDIController::class, 'dashboardGestor'])->name('dashboard.gestor');
    Route::get('/lider/dashboard', [SISGEDIController::class, 'dashboardLider'])->name('dashboard.lider');
    Route::get('/documentos-guia/{tarea_id}', [SISGEDIController::class, 'verDocumentoGuia'])->name('documento_guia.show');
    Route::get('/documentos-guia/{tarea_id}/archivo', [SISGEDIController::class, 'archivoDocumentoGuia'])->name('documento_guia.file');
    Route::get('/documentos-guia/{tarea_id}/descargar', [SISGEDIController::class, 'descargarDocumentoGuia'])->name('documento_guia.download');
    Route::get('/evidencias/{evidencia_id}', [SISGEDIController::class, 'verEvidencia'])->name('evidencia.show');
    Route::get('/evidencias/{evidencia_id}/archivo', [SISGEDIController::class, 'archivoEvidencia'])->name('evidencia.file');
    Route::get('/evidencias/{evidencia_id}/descargar', [SISGEDIController::class, 'descargarEvidencia'])->name('evidencia.download');

    // ── Módulo: Tareas Recibidas (Asignadas por Gerente Administrativo) ──
    Route::get('/tareas-recibidas', [SISGEDIController::class, 'tareasRecibidas'])->name('tareas_recibidas');

    // ── Módulo: Tareas para Líderes (Gestor) ───────────────
    Route::get('/gestor/tareas-lideres',  [SISGEDIController::class, 'tareasLideres'])->name('gestor.tareas_lideres');
    Route::post('/gestor/tareas-lideres', [SISGEDIController::class, 'storeTareaLider'])->name('gestor.tareas_lideres.store');

    // ── Módulo: Revisión de Evidencias (Gestor) ─────────────
    Route::get('/gestor/revision-evidencias',          [SISGEDIController::class, 'revisionEvidencias'])->name('gestor.revision_evidencias');
    Route::post('/gestor/revision-evidencias/aprobar',  [SISGEDIController::class, 'aprobarEvidencia'])->name('gestor.revision_evidencias.aprobar');
    Route::post('/gestor/revision-evidencias/rechazar', [SISGEDIController::class, 'rechazarEvidencia'])->name('gestor.revision_evidencias.rechazar');

    // ── Módulo: Mis Evidencias (Líder envía evidencias al Gestor) ──
    Route::get('/lider/mis-evidencias',  [SISGEDIController::class, 'liderMisEvidencias'])->name('lider.mis_evidencias');
    Route::post('/lider/mis-evidencias', [SISGEDIController::class, 'liderStorEvidencia'])->name('lider.evidencias.store');

    // ── Módulo: Tareas para Colaboradores (Líder asigna tareas a su equipo) ──
    Route::get('/lider/tareas-colaboradores',  [SISGEDIController::class, 'liderTareasColaboradores'])->name('lider.tareas_colaboradores');
    Route::post('/lider/tareas-colaboradores', [SISGEDIController::class, 'liderStoreTareaColaborador'])->name('lider.tareas_colaboradores.store');


    // ── Perfil del usuario con sesión SISGEDI ──────────────────────
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');

    // ── Plan de trabajo de la fase (RN-028: visible para todos los roles) ──
    Route::get('/plan-trabajo', [PlanTrabajoController::class, 'show'])->name('plan-trabajo.show');

    // ── CRUD Documentos ───────────────────────────────────────────
    Route::get('/elementos/create',    [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos',          [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}',      [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}',   [DocumentoController::class, 'destroy'])->name('destroy');

    // ── Gestión de Fases ───────────────────────────────────────────
    Route::prefix('fases')->name('fases.')->group(function () {
        Route::get('/',          [FaseController::class, 'index'])->name('index');
        Route::get('/crear',     [FaseController::class, 'create'])->name('create');
        Route::post('/',         [FaseController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [FaseController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [FaseController::class, 'update'])->name('update');
        Route::post('/{id}/desactivar', [FaseController::class, 'desactivar'])->name('desactivar');
        Route::delete('/{id}',   [FaseController::class, 'destroy'])->name('destroy');
    });

    // ── Convocatorias y Selección (panel admin) ───────────────────
    Route::prefix('convocatorias')->name('convocatorias.')->group(function () {
        Route::get('/',             [ConvocatoriaController::class, 'index'])->name('index');
        Route::get('/crear',        [ConvocatoriaController::class, 'create'])->name('create');
        Route::post('/',            [ConvocatoriaController::class, 'store'])->name('store');
        Route::get('/{id}',         [ConvocatoriaController::class, 'show'])->name('show');
        Route::get('/{id}/editar',  [ConvocatoriaController::class, 'edit'])->name('edit');
        Route::put('/{id}',         [ConvocatoriaController::class, 'update'])->name('update');
        Route::delete('/{id}',      [ConvocatoriaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-estado',   [ConvocatoriaController::class, 'toggleEstado'])->name('toggleEstado');
        Route::get('/resultados/seleccion', [ConvocatoriaController::class, 'resultados'])->name('resultados');
        Route::get('/reasignacion/lista',   [ConvocatoriaController::class, 'reasignacion'])->name('reasignacion');
        Route::get('/checklist/entrevista', [ConvocatoriaController::class, 'checklist'])->name('checklist');
    });

    // ── Realización de Entrevistas ──────────────────────────────────
    Route::prefix('entrevistas')->name('entrevistas.')->group(function () {
        Route::get('/',                      [EntrevistaController::class, 'index'])->name('index');
        Route::get('/postulacion/{id}',      [EntrevistaController::class, 'create'])->name('create');
        Route::post('/postulacion/{id}',     [EntrevistaController::class, 'store'])->name('store');
    });

    // ── Panel del Aprendiz ────────────────────────────────────────
    Route::prefix('aprendiz')->name('aprendiz.')->group(function () {
        // Panel principal: ver convocatorias abiertas
        Route::get('/panel',                         [PostulacionController::class, 'panel'])->name('panel');
        // Ver detalle de una convocatoria y postularse
        Route::get('/convocatoria/{id}',             [PostulacionController::class, 'verConvocatoria'])->name('convocatoria');
        // Guardar postulación (3 cargos)
        Route::post('/convocatoria/{id}/postular',   [PostulacionController::class, 'postular'])->name('postular');
    });

    // ── Panel del Gerente Administrativo (RF-019: cascada de tareas) ──
    // Sesión SISGEDI (users_sisgedi/roles_sisgedi) + rol "GerenteAdministrativo"
    // activo en la fase vigente (tabla `fase`).
    Route::prefix('gerente')
        ->name('gerente.')
        ->middleware(['rol.sisgedi:GerenteAdministrativo'])
        ->group(function () {
            Route::get('/dashboard', [GerenteDashboardController::class, 'index'])->name('dashboard');

            Route::get('/tareas',              [GerenteTareaController::class, 'index'])->name('tareas.index');
            Route::get('/tareas/crear',        [GerenteTareaController::class, 'create'])->name('tareas.create');
            Route::post('/tareas',             [GerenteTareaController::class, 'store'])->name('tareas.store');

            Route::get('/plan-trabajo',  [GerentePlanTrabajoController::class, 'edit'])->name('plan-trabajo.edit');
            Route::post('/plan-trabajo', [GerentePlanTrabajoController::class, 'save'])->name('plan-trabajo.save');
        });
});
