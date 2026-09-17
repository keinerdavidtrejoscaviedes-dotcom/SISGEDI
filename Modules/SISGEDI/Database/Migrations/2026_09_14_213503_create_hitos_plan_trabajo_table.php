<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Hito (entrega, reunión u otro evento) definido dentro de un plan de trabajo.
     */
    public function up(): void
    {
        Schema::create('hitos_plan_trabajo', function (Blueprint $table) {
            $table->id('hito_id');
            $table->unsignedBigInteger('plan_trabajo_id');
            $table->string('titulo');
            $table->date('fecha');
            $table->enum('tipo', ['entrega', 'reunion', 'hito']);
            $table->timestamps();

            $table->foreign('plan_trabajo_id')
                  ->references('plan_trabajo_id')
                  ->on('planes_trabajo')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hitos_plan_trabajo');
    }
};
