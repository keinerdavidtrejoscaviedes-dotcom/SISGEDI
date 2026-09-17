<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha_limite');
            $table->enum('tipo_evidencia_requerida', ['foto', 'video-enlace', 'documento']);
            $table->enum('estado', [
                'planificada',
                'asignada',
                'en_desarrollo',
                'enviada',
                'en_revision',
                'aprobada',
                'rechazada',
                'vencida',
            ])->default('planificada');
            $table->enum('clasificacion', ['general', 'individual', 'cascada'])->default('cascada');
            // `generador_usuario_id` apunta a `users_sisgedi.id_users` (int
            // con signo, la identidad usada por la sesión de SISGEDI).
            $table->integer('generador_usuario_id');
            $table->foreignId('tarea_padre_id')->nullable()->constrained('sisgedi_tareas')->nullOnDelete();
            $table->foreignId('sector_id')->constrained('sisgedi_sectores_productivos')->cascadeOnDelete();
            // `fase_id` apunta a la tabla legacy `fase` (bigint con signo).
            $table->bigInteger('fase_id');
            $table->boolean('confirmada')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('generador_usuario_id')->references('id_users')->on('users_sisgedi')->cascadeOnDelete();
            $table->foreign('fase_id')->references('fase_id')->on('fase')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_tareas');
    }
};
