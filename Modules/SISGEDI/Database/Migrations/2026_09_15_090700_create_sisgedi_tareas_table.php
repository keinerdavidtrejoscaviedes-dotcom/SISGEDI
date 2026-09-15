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
            $table->foreignId('generador_usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tarea_padre_id')->nullable()->constrained('sisgedi_tareas')->nullOnDelete();
            $table->foreignId('sector_id')->constrained('sisgedi_sectores_productivos')->cascadeOnDelete();
            $table->foreignId('fase_id')->constrained('sisgedi_fases')->cascadeOnDelete();
            $table->boolean('confirmada')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_tareas');
    }
};
