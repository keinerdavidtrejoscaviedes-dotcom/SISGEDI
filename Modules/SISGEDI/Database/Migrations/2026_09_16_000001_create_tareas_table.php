<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id('tarea_id');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_limite')->nullable();
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
            $table->unsignedInteger('gestor_usuario_id')->nullable();
            $table->unsignedInteger('fase_id')->nullable();
            $table->string('sector')->nullable();
            $table->string('evidencia_esperada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
