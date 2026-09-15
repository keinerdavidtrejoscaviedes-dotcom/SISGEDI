<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisgedi_roles', function (Blueprint $table) {
            $table->id();
            $table->enum('nombre_rol', [
                'Administrador',
                'GerenteAdministrativo',
                'Gestor',
                'Lider',
                'Colaborador',
                'Instructor',
                'Aprendiz',
            ])->unique();
            $table->boolean('es_dinamico'); // true = Gestor/Lider/Gerente; false = Administrador/Instructor/Aprendiz
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisgedi_roles');
    }
};
