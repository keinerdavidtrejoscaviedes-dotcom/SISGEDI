<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_planes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('fase_id')->constrained('sisgedi_fases')->cascadeOnDelete();
            $table->enum('nivel', ['general', 'area', 'equipo'])->default('general');
            $table->dateTime('fecha_publicacion');
            $table->text('descripcion');
            $table->timestamps();

            // RN-028: un unico plan por nivel y autor dentro de cada fase
            // (para "general" en la practica es un unico plan por fase, ya
            // que solo el Gerente Administrativo lo publica).
            $table->unique(['fase_id', 'nivel', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_planes_trabajo');
    }
};
