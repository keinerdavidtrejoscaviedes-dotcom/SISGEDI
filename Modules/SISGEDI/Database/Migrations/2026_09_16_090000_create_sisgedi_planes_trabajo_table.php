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
            // `user_id` apunta a `users_sisgedi.id_users` (int con signo).
            $table->integer('user_id');
            // `fase_id` apunta a la tabla legacy `fase` (bigint con signo).
            $table->bigInteger('fase_id');
            $table->enum('nivel', ['general', 'area', 'equipo'])->default('general');
            $table->dateTime('fecha_publicacion');
            $table->text('descripcion');
            $table->timestamps();

            $table->foreign('user_id')->references('id_users')->on('users_sisgedi')->cascadeOnDelete();
            $table->foreign('fase_id')->references('fase_id')->on('fase')->cascadeOnDelete();

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
