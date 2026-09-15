<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_documentos_guia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->unique()->constrained('sisgedi_tareas')->cascadeOnDelete();
            $table->text('instrucciones');
            $table->text('entregables_esperados');
            $table->text('plazos');
            $table->string('archivo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_documentos_guia');
    }
};
