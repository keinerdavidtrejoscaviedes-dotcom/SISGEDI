<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Instancia de activacion de un rol para un usuario (modelo de roles dinamicos).
     * Un usuario puede tener varios registros activos segun el cargo y la fase.
     */
    public function up(): void
    {
        Schema::create('sisgedi_usuario_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sisgedi_rol_id')->constrained('sisgedi_roles')->cascadeOnDelete();
            $table->foreignId('cargo_id')->nullable()->constrained('sisgedi_cargos')->nullOnDelete();
            $table->foreignId('fase_id')->nullable()->constrained('sisgedi_fases')->nullOnDelete();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->dateTime('fecha_activacion')->nullable();
            $table->dateTime('fecha_desactivacion')->nullable();
            $table->foreignId('activado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('activacion_automatica')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_usuario_roles');
    }
};
