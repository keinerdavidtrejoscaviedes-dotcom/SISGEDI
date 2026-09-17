<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;
use Modules\SISGEDI\Http\Controllers\GerenteDashboardController;
use Modules\SISGEDI\Http\Controllers\GerenteInstructorController;
use Modules\SISGEDI\Http\Controllers\GerentePlanTrabajoController;
use Modules\SISGEDI\Http\Controllers\GerenteTareaController;
use Modules\SISGEDI\Http\Controllers\PlanTrabajoController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {

    // ── Públicas (sin sesión requerida) ──────────────────────
    Route::get('/',        [DocumentoController::class,  'index'])->name('index');
    Route::post('/login',  [AuthSisgediController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthSisgediController::class, 'logout'])->name('logout');

    // ── Dashboard (requiere sesión SISGEDI) ──────────────────
    Route::get('/dashboard', [DocumentoController::class, 'dashboard'])->name('dashboard');

    // ── Plan de trabajo de la fase (RN-028: visible para todos los roles) ──
    Route::get('/plan-trabajo', [PlanTrabajoController::class, 'show'])->name('plan-trabajo.show');

    // ── CRUD Documentos ──────────────────────────────────────
    Route::get('/elementos/create',    [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos',          [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}',      [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}',   [DocumentoController::class, 'destroy'])->name('destroy');

    // ── Panel del Gerente Administrativo (RF-019: cascada de tareas) ──
    // Auth central del ERP + rol dinamico "GerenteAdministrativo" activo en la fase vigente.
    Route::prefix('gerente')
        ->name('gerente.')
        ->middleware(['auth', 'rol.sisgedi:GerenteAdministrativo'])
        ->group(function () {
            Route::get('/dashboard', [GerenteDashboardController::class, 'index'])->name('dashboard');

            Route::get('/tareas',              [GerenteTareaController::class, 'index'])->name('tareas.index');
            Route::get('/tareas/crear',        [GerenteTareaController::class, 'create'])->name('tareas.create');
            Route::post('/tareas',             [GerenteTareaController::class, 'store'])->name('tareas.store');

            Route::get('/plan-trabajo',  [GerentePlanTrabajoController::class, 'edit'])->name('plan-trabajo.edit');
            Route::post('/plan-trabajo', [GerentePlanTrabajoController::class, 'save'])->name('plan-trabajo.save');

            Route::get('/instructores',                  [GerenteInstructorController::class, 'index'])->name('instructores.index');
            Route::get('/instructores/crear',             [GerenteInstructorController::class, 'create'])->name('instructores.create');
            Route::post('/instructores',                  [GerenteInstructorController::class, 'store'])->name('instructores.store');
            Route::get('/instructores/{instructor}/editar', [GerenteInstructorController::class, 'edit'])->name('instructores.edit');
            Route::put('/instructores/{instructor}',      [GerenteInstructorController::class, 'update'])->name('instructores.update');
            Route::delete('/instructores/{instructor}',   [GerenteInstructorController::class, 'destroy'])->name('instructores.destroy');
        });
});