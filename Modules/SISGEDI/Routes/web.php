<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\GerenteComercialController;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {

    Route::prefix('comercial')->name('comercial.')->group(function () {
        Route::get('/producto', [GerenteComercialController::class, 'portafolioProducto'])->name('producto');
        Route::post('/producto', [GerenteComercialController::class, 'storeProducto'])->name('producto.store');
        Route::put('/producto/{id}', [GerenteComercialController::class, 'updateProducto'])->name('producto.update');
        Route::delete('/producto/{id}', [GerenteComercialController::class, 'destroyProducto'])->name('producto.destroy');
        Route::get('/dashboard', [GerenteComercialController::class, 'dasboardComercial'])->name('dashboard');
    });

    // ── Públicas ──────────────────────────────────────────────────
    Route::get('/',        [DocumentoController::class,  'index'])->name('index');
    Route::get('/login',   [AuthSisgediController::class, 'showLoginForm'])->name('login');
    Route::post('/login',  [AuthSisgediController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthSisgediController::class, 'logout'])->name('logout');

    // ── Dashboard (admin / gestor) ────────────────────────────────
    Route::get('/dashboard', [DocumentoController::class, 'dashboard'])->name('dashboard');

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
});
