<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\GerenteComercialController;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;
use Modules\SISGEDI\Http\Controllers\GerenteDashboardController;
use Modules\SISGEDI\Http\Controllers\GerentePlanTrabajoController;
use Modules\SISGEDI\Http\Controllers\GerenteTareaController;
use Modules\SISGEDI\Http\Controllers\PlanTrabajoController;

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

    // ── Plan de trabajo de la fase (RN-028: visible para todos los roles) ──
    Route::get('/plan-trabajo', [PlanTrabajoController::class, 'show'])->name('plan-trabajo.show');

    // ── CRUD Documentos ───────────────────────────────────────────
    Route::get('/elementos/create',    [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos',          [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}',      [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}',   [DocumentoController::class, 'destroy'])->name('destroy');
});