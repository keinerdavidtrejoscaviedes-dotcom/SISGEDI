<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;
use Modules\SISGEDI\Http\Controllers\SISGEDIController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {

    Route::get('/assets/gestor-dashboard.css', function () {
        return response()->file(
            module_path('SISGEDI', 'Resources/assets/css/gestor-dashboard.css'),
            ['Content-Type' => 'text/css']
        );
    })->name('assets.gestor_css');

    // ── Públicas (sin sesión requerida) ──────────────────────
    Route::get('/',                            [DocumentoController::class,   'index'])->name('index');
    Route::post('/login',                      [AuthSisgediController::class, 'login'])->name('login.post');
    Route::match(['get', 'post'], '/logout',   [AuthSisgediController::class, 'logout'])->name('logout');

    // ── Dashboard (requiere sesión SISGEDI) ──────────────────
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


    // ── CRUD Documentos ──────────────────────────────────────
    Route::get('/elementos/create',    [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos',          [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}',      [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}',   [DocumentoController::class, 'destroy'])->name('destroy');
});