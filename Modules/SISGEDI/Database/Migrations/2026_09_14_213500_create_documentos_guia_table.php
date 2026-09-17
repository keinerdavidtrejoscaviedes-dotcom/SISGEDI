<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Documento con las instrucciones y entregables esperados de una tarea (Relación 1:1 con Tarea).
     */
    public function up(): void
    {
        Schema::create('documentos_guia', function (Blueprint $table) {
            $table->id('documento_guia_id');
            $table->unsignedBigInteger('tarea_id')->unique();
            $table->longText('instrucciones');
            $table->longText('entregables_esperados');
            $table->longText('plazos');
            $table->string('archivo_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_guia');
    }
};
