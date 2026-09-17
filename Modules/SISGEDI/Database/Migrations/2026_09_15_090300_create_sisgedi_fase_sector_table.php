<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_fase_sector', function (Blueprint $table) {
            // `fase_id` apunta a la tabla legacy `fase` (bigint con signo,
            // no unsigned), por eso no se usa foreignId()/constrained() aquí.
            $table->bigInteger('fase_id');
            $table->foreignId('sector_id')->constrained('sisgedi_sectores_productivos')->cascadeOnDelete();
            $table->boolean('estado_activo')->default(true);
            $table->timestamps();

            $table->primary(['fase_id', 'sector_id']);
            $table->foreign('fase_id')->references('fase_id')->on('fase')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_fase_sector');
    }
};
