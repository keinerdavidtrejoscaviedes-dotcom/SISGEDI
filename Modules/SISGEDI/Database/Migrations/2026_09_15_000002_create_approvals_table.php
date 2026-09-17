<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('deliverable_id')->comment('ID del entregable');
            $table->string('deliverable_name')->comment('Nombre del entregable');
            $table->integer('colaborador_id')->comment('ID del colaborador en users_sisgedi');
            $table->string('colaborador_name')->comment('Nombre del colaborador');
            $table->integer('instructor_id')->comment('ID del instructor que revisa');
            $table->string('instructor_name')->comment('Nombre del instructor');
            $table->string('status')->default('pendiente')->comment('Estado: pendiente, aprobado, rechazado');
            $table->text('feedback')->nullable()->comment('Comentarios/razones del instructor');
            $table->string('file_path')->comment('Ruta del archivo entregado');
            $table->string('file_type')->comment('Tipo de archivo');
            $table->integer('signature_id')->nullable()->comment('ID de la firma usada');
            $table->timestamp('submitted_at')->comment('Fecha de entrega del colaborador');
            $table->timestamp('reviewed_at')->nullable()->comment('Fecha de revisión del instructor');
            $table->timestamps();
            
            $table->index('deliverable_id');
            $table->index('colaborador_id');
            $table->index('instructor_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
