<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo_cargo', ['GERENTE_ADMINISTRATIVO', 'GESTOR', 'LIDER', 'COLABORADOR']);
            $table->foreignId('gerencia_id')->constrained('sisgedi_gerencias')->cascadeOnDelete();
            $table->foreignId('cargo_superior_id')->nullable()->constrained('sisgedi_cargos')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_cargos');
    }
};
