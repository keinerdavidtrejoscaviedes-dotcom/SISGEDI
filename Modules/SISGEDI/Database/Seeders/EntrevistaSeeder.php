<?php

namespace Modules\SISGEDI\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EntrevistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // 1. Crear un checklist vigente
        $checklistId = DB::table('checklist_entrevista')->insertGetId([
            'nombre'         => 'Checklist Entrevista Estándar',
            'version'        => 1,
            'vigente'        => 1,
            'fecha_creacion' => now(),
            'creado_por'     => 1, // Usuario administrador genérico (de usuario table)
        ]);

        // 2. Crear los ítems del checklist
        $items = [
            ['nombre_item' => 'Presentación personal y actitud', 'tipo_respuesta' => 'calificacion', 'orden' => 1],
            ['nombre_item' => 'Conocimiento técnico del área', 'tipo_respuesta' => 'calificacion', 'orden' => 2],
            ['nombre_item' => 'Experiencia previa relevante', 'tipo_respuesta' => 'calificacion', 'orden' => 3],
            ['nombre_item' => 'Disponibilidad de horario', 'tipo_respuesta' => 'texto', 'orden' => 4],
            ['nombre_item' => 'Manejo de herramientas tecnológicas', 'tipo_respuesta' => 'calificacion', 'orden' => 5],
            ['nombre_item' => 'Observaciones adicionales', 'tipo_respuesta' => 'texto', 'orden' => 6],
        ];

        foreach ($items as $item) {
            DB::table('item_checklist')->insert([
                'checklist_id'   => $checklistId,
                'nombre_item'    => $item['nombre_item'],
                'tipo_respuesta' => $item['tipo_respuesta'],
                'orden'          => $item['orden'],
            ]);
        }
    }
}
