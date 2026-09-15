<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('entrevista')) {
            // Because calificacion_item_entrevista references entrevista_id as BIGINT (signed),
            // and postulacion_id is BIGINT (signed), and users_sisgedi.id_users is INT (signed).
            // We need to use raw SQL or precise blueprint to match exact types.
            
            Schema::create('entrevista', function (Blueprint $table) {
                // To create a signed big integer auto increment primary key
                $table->bigInteger('entrevista_id', true);
                
                $table->bigInteger('postulacion_id')->comment('La postulación a la que pertenece la entrevista.');
                $table->integer('evaluador_id')->comment('El usuario evaluador (users_sisgedi) que realizó la entrevista.');
                $table->datetime('fecha_entrevista')->comment('Fecha y hora en que se realizó la entrevista.');
                $table->text('observaciones')->nullable()->comment('Observaciones generales de la entrevista.');
                $table->timestamps();

                $table->foreign('postulacion_id', 'fk_entrevista_postulacion_id')
                      ->references('postulacion_id')->on('postulacion')
                      ->onDelete('cascade');
                      
                $table->foreign('evaluador_id', 'fk_entrevista_evaluador_sisgedi')
                      ->references('id_users')->on('users_sisgedi')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('entrevista');
    }
};
