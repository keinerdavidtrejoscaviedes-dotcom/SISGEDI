<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_fases', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Sol 2026-II
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['configurada', 'activa', 'finalizada'])->default('configurada');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_fases');
    }
};
