<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_fase_sector', function (Blueprint $table) {
            $table->foreignId('fase_id')->constrained('sisgedi_fases')->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained('sisgedi_sectores_productivos')->cascadeOnDelete();
            $table->boolean('estado_activo')->default(true);
            $table->timestamps();

            $table->primary(['fase_id', 'sector_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_fase_sector');
    }
};
