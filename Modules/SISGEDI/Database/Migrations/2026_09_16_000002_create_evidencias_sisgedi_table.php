<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidencias_sisgedi', function (Blueprint $table) {
            $table->id('evidencia_id');
            $table->unsignedBigInteger('tarea_asignacion_id')->nullable();
            $table->unsignedInteger('usuario_id')->nullable();
            $table->string('codigo')->unique();
            $table->string('archivo')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->enum('estado', [
                'enviada',
                'en_revision',
                'aprobada',
                'rechazada',
            ])->default('enviada');
            $table->text('motivo_rechazo')->nullable();
            $table->dateTime('fecha_carga')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencias_sisgedi');
    }
};
