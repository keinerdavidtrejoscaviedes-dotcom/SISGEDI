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
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approval_id')->comment('FK a la tabla approvals');
            $table->unsignedBigInteger('colaborador_id')->comment('ID del colaborador que carga la evidencia');
            $table->string('file_path')->comment('Ruta del archivo de evidencia');
            $table->string('file_name')->comment('Nombre original del archivo');
            $table->string('file_type')->comment('Tipo de archivo (PDF, Word, Excel, Image, etc)');
            $table->integer('file_size')->comment('Tamaño del archivo en KB');
            $table->text('description')->nullable()->comment('Descripción opcional de la evidencia');
            $table->timestamp('uploaded_at')->comment('Fecha y hora de carga');
            $table->timestamps();
            
            $table->foreign('approval_id')
                ->references('id')
                ->on('approvals')
                ->onDelete('cascade');
            
            $table->index('approval_id');
            $table->index('colaborador_id');
            $table->index('uploaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
