<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;
use Modules\SISGEDI\Http\Controllers\AuthSisgediController;
use Modules\SISGEDI\Http\Controllers\ColaboradorDashboardController;
use Modules\SISGEDI\Http\Controllers\InstructorDashboardController;
use Modules\SISGEDI\Http\Controllers\SignatureController;
use Modules\SISGEDI\Http\Controllers\ApprovalController;
use Modules\SISGEDI\Http\Controllers\EvidenceController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {

    // ── Públicas (sin sesión requerida) ──────────────────────
    Route::get('/',        [DocumentoController::class,  'index'])->name('index');
    Route::post('/login',  [AuthSisgediController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthSisgediController::class, 'logout'])->name('logout');

    // ── Dashboard (requiere sesión SISGEDI) ──────────────────
    Route::get('/dashboard', [DocumentoController::class, 'dashboard'])->name('dashboard');

    // ── CRUD Documentos ──────────────────────────────────────
    Route::get('/elementos/create',    [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos',          [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}',      [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}',   [DocumentoController::class, 'destroy'])->name('destroy');

    // ── DASHBOARDS COLABORADOR (HU-001 a HU-009) ──────────────
    Route::prefix('colaborador')->name('colaborador.')->group(function () {
        Route::get('/dashboard',           [ColaboradorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/mis-tareas',          [ColaboradorDashboardController::class, 'misTareas'])->name('mis-tareas');
        Route::get('/bitacoras',           [ColaboradorDashboardController::class, 'bitacoras'])->name('bitacoras');
        Route::get('/paz-y-salvo',         [ColaboradorDashboardController::class, 'pazYSalvo'])->name('paz-y-salvo');
        Route::get('/plan-innovacion',     [ColaboradorDashboardController::class, 'planInnovacion'])->name('plan-innovacion');
        Route::get('/convocatorias',       [ColaboradorDashboardController::class, 'convocatorias'])->name('convocatorias');
        Route::get('/mis-evidencias',      [ColaboradorDashboardController::class, 'misEvidencias'])->name('mis-evidencias');
        Route::get('/doc-final-fase',      [ColaboradorDashboardController::class, 'docFinalFase'])->name('doc-final-fase');

        // ── Rutas de Evidencias ──────────────────────────────
        Route::post('/evidences/{approval_id}/upload', [EvidenceController::class, 'upload'])->name('evidences.upload');
        Route::get('/evidences/{id}/download',        [EvidenceController::class, 'download'])->name('evidences.download');
        Route::post('/evidences/{id}/delete',         [EvidenceController::class, 'delete'])->name('evidences.delete');
    });

    // ── DASHBOARDS INSTRUCTOR (HU-010 a HU-013) ──────────────
    Route::prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/dashboard',           [InstructorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/cambiar-contexto/{contexto}', [InstructorDashboardController::class, 'cambiarContexto'])->name('cambiar-contexto');
        Route::get('/fichas-asignadas',     [InstructorDashboardController::class, 'fichasAsignadas'])->name('fichas-asignadas');
        Route::get('/colaborador/{id}',     [InstructorDashboardController::class, 'detalleColaborador'])->name('detalle-colaborador');
        Route::get('/revisar-entregables',  [InstructorDashboardController::class, 'revisarEntregables'])->name('revisar-entregables');
        Route::get('/gestionar-firma',      [InstructorDashboardController::class, 'gestionarFirma'])->name('gestionar-firma');
        
        // ── Notificaciones ───────────────────────────────────
        Route::get('/get-notifications',   [InstructorDashboardController::class, 'getNotifications'])->name('get-notifications');
        Route::post('/mark-notification-read', [InstructorDashboardController::class, 'markNotificationRead'])->name('mark-notification-read');

        // ── Rutas de Firmas ──────────────────────────────────
        Route::post('/signatures/upload',   [SignatureController::class, 'upload'])->name('signatures.upload');
        Route::get('/signatures/download/{id}', [SignatureController::class, 'download'])->name('signatures.download');
        Route::post('/signatures/replace',  [SignatureController::class, 'replace'])->name('signatures.replace');

        // ── Rutas de Aprobaciones ────────────────────────────
        Route::post('/approvals/{id}/approve',  [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject',   [ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/mock-create',   [ApprovalController::class, 'createMock'])->name('approvals.mock');
    });
});