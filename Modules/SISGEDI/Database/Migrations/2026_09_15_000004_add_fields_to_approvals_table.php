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
        Schema::table('approvals', function (Blueprint $table) {
            // Campos para tracking del líder/creador
            $table->integer('leader_id')->nullable()->after('instructor_id')->comment('ID del líder que asignó la tarea');
            $table->string('leader_name')->nullable()->after('leader_id')->comment('Nombre del líder');
            $table->timestamp('assigned_at')->nullable()->after('submitted_at')->comment('Fecha de asignación de la tarea');
            $table->timestamp('deadline')->nullable()->after('assigned_at')->comment('Plazo para entregar la tarea');
            
            // Para múltiples aprobadores
            $table->json('approved_by')->nullable()->after('reviewed_at')->comment('JSON con datos de todos los aprobadores');
            
            $table->index('leader_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropIndex(['leader_id']);
            $table->dropColumn(['leader_id', 'leader_name', 'assigned_at', 'deadline', 'approved_by']);
        });
    }
};
