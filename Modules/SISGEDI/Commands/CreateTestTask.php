<?php

namespace Modules\SISGEDI\Commands;

use Illuminate\Console\Command;
use Modules\SISGEDI\Entities\Approval;
use Modules\SISGEDI\Entities\Signature;

class CreateTestTask extends Command
{
    protected $signature = 'sisgedi:test-task';
    protected $description = 'Crear una tarea de prueba para testing del sistema de evidencias y firmas';

    public function handle()
    {
        $this->info('═════════════════════════════════════════════');
        $this->info('  Creando Tarea de Prueba - SISGEDI Testing');
        $this->info('═════════════════════════════════════════════');

        // Verificar que exista firma del instructor
        $this->line("\n📋 Paso 1: Verificando instructor y firma...");
        $instructor = Signature::getActive(1);
        
        if (!$instructor) {
            $this->warn('   ⚠ No hay firma activa. Creando una...');
            $signature = Signature::create([
                'instructor_id' => 1,
                'instructor_name' => 'Dr. Juan López García',
                'file_path' => 'storage/signatures/test_firma_1.png',
                'file_type' => 'PNG',
                'file_size' => 128,
                'version' => 'v1',
                'is_active' => true,
                'uploaded_at' => now(),
            ]);
            $this->info("   ✓ Firma creada (ID: {$signature->id})");
        } else {
            $this->info("   ✓ Firma activa encontrada: {$instructor->version}");
        }

        // Crear tarea de prueba
        $this->line("\n📝 Paso 2: Creando tarea de prueba...");
        $approval = Approval::create([
            'deliverable_id' => rand(9000, 9999),
            'deliverable_name' => 'Prueba: Informe de Actividades Semanal',
            'colaborador_id' => 5,
            'colaborador_name' => 'Carlos Testero',
            'instructor_id' => 1,
            'instructor_name' => 'Dr. Juan López García',
            'status' => 'pendiente',
            'file_path' => 'uploads/plantilla_informe.pdf',
            'file_type' => 'PDF',
            'submitted_at' => now(),
        ]);
        
        $this->info('✓ Tarea creada exitosamente');
        $this->line("  ID: {$approval->id}");
        $this->line("  Colaborador: {$approval->colaborador_name} (ID: {$approval->colaborador_id})");
        $this->line("  Instructor: {$approval->instructor_name} (ID: {$approval->instructor_id})");
        $this->line("  Estado: {$approval->status}");

        // Mostrar instrucciones
        $this->line("\n🚀 CREDENCIALES DE PRUEBA:");
        $this->line("═════════════════════════════════════════════");
        
        $this->table(['Rol', 'ID Usuario', 'Nombre'], [
            ['Colaborador', '5', 'Carlos Testero'],
            ['Instructor', '1', 'Dr. Juan López García'],
        ]);

        $this->line("\n📌 PASOS DE PRUEBA:");
        $this->line("═════════════════════════════════════════════");
        $this->line("1. COLABORADOR SUBE EVIDENCIA:");
        $this->line("   • Login como: ID 5");
        $this->line("   • Navega a: /sisgedi/colaborador/mis-tareas");
        $this->line("   • Busca: 'Prueba: Informe de Actividades Semanal'");
        $this->line("   • Haz clic en '+ Subir Evidencia'");
        $this->line("   • Selecciona un archivo y sube");
        
        $this->line("\n2. INSTRUCTOR REVISA Y FIRMA:");
        $this->line("   • Logout y login como: ID 1");
        $this->line("   • Navega a: /sisgedi/instructor/revisar-entregables");
        $this->line("   • Busca la tarea de prueba");
        $this->line("   • Haz clic en 'Firmar y Aprobar'");
        
        $this->line("\n3. VERIFICA LA FIRMA:");
        $this->line("   • Navega a: /sisgedi/instructor/gestionar-firma");
        $this->line("   • Ve el contador de 'docs firmados' aumentar");

        $this->line("\n📊 INFORMACIÓN DE PRUEBA:");
        $this->line("═════════════════════════════════════════════");
        $this->info("Approval ID: {$approval->id}");
        $this->info("Deliverable ID: {$approval->deliverable_id}");
        $this->info("Estado: PENDIENTE (cambiar a APROBADO cuando instructor firme)");
        
        $this->line("\n✓ Tarea de prueba lista. Abre el navegador y comienza a probar!");
        $this->line("Guía completa: Modules/SISGEDI/TESTING_GUIDE.md");
        $this->info('═════════════════════════════════════════════');
    }
}
