<?php

use Illuminate\Database\Migrations\Migration;

/**
 * No-op: el concepto de "fase" se unificó con la tabla legacy `fase`
 * (gestionada desde FaseController / Gestión de Fases) para tener una
 * única fuente de verdad de la fase vigente. Ver Entities/Fase.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
