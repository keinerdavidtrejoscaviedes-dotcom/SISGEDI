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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->integer('instructor_id')->comment('ID del instructor en users_sisgedi');
            $table->string('instructor_name')->comment('Nombre del instructor');
            $table->string('file_path')->comment('Ruta del archivo de firma');
            $table->string('file_type')->comment('Tipo de archivo (PNG, JPG, etc)');
            $table->integer('file_size')->comment('Tamaño del archivo en KB');
            $table->string('version')->comment('Versión de la firma (v1, v2, v3, etc)');
            $table->boolean('is_active')->default(true)->comment('¿Es la firma vigente?');
            $table->timestamp('uploaded_at')->useCurrent()->comment('Fecha de carga');
            $table->timestamps();
            
            $table->index('instructor_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
