<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_hitos_plan_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_trabajo_id')->constrained('sisgedi_planes_trabajo')->cascadeOnDelete();
            $table->string('titulo');
            $table->date('fecha');
            $table->enum('tipo', ['entrega', 'reunion', 'hito'])->default('hito');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_hitos_plan_trabajo');
    }
};
