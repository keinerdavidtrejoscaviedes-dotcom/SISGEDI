<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_instructores', function (Blueprint $table) {
            $table->id();
            // Nulo si el instructor no tiene cuenta propia en el sistema.
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre_completo');
            $table->string('area_especialidad');
            $table->string('correo');
            $table->string('telefono');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->string('firma_digital_url')->nullable();
            $table->foreignId('gestionado_por')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_instructores');
    }
};
