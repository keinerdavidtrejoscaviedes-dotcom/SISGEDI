<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fase', function (Blueprint $table) {
            if (! Schema::hasColumn('fase', 'tipo')) {
                $table->string('tipo')->nullable()->after('nombre_fase');
            }
            if (! Schema::hasColumn('fase', 'fecha_inicio')) {
                $table->date('fecha_inicio')->nullable()->after('tipo');
            }
            if (! Schema::hasColumn('fase', 'fecha_fin')) {
                $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            }
            if (! Schema::hasColumn('fase', 'estado')) {
                $table->string('estado')->default('Pendiente')->after('fecha_fin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fase', function (Blueprint $table) {
            foreach (['tipo', 'fecha_inicio', 'fecha_fin', 'estado'] as $column) {
                if (Schema::hasColumn('fase', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
