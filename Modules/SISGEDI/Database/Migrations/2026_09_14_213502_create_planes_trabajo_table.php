<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Plan de trabajo publicado por un Gerente, Gestor o Líder para un nivel (general, de área o de equipo) en una fase.
     */
    public function up(): void
    {
        Schema::create('planes_trabajo', function (Blueprint $table) {
            $table->id('plan_trabajo_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('fase_id');
            $table->enum('nivel', ['general', 'area', 'equipo']);
            $table->dateTime('fecha_publicacion');
            $table->longText('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_trabajo');
    }
};
