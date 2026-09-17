<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabla puente que vincula una tarea con cada colaborador asignado a ejecutarla.
     */
    public function up(): void
    {
        Schema::create('tarea_asignaciones', function (Blueprint $table) {
            $table->id('tarea_asignacion_id');
            $table->unsignedBigInteger('tarea_id');
            $table->unsignedBigInteger('colaborador_usuario_id');
            $table->enum('estado_individual', [
                'pendiente',
                'en_desarrollo',
                'enviada',
                'aprobada',
                'rechazada'
            ])->default('pendiente');
            $table->dateTime('fecha_asignacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_asignaciones');
    }
};
