<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\Approval;
use Modules\SISGEDI\Entities\Signature;

class ApprovalController extends Controller
{
    /**
     * Mostrar entregables pendientes para revisar
     */
    public function pending()
    {
        $usuario = session('sisgedi_user');
        $instructor_id = $usuario['id'];

        $pending = Approval::getPendingByInstructor($instructor_id);

        return view('sisgedi::instructor.revisar-entregables', compact('pending'));
    }

    /**
     * Aprobar un entregable
     */
    public function approve(Request $request, $id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $approval = Approval::findOrFail($id);

        // Verificar que el usuario sea el instructor asignado
        if ($approval->instructor_id != $usuario['id']) {
            return redirect()->back()->with('error', 'No tienes permiso para aprobar este entregable.');
        }

        try {
            // Obtener firma activa del instructor
            $signature = Signature::getActive($usuario['id']);

            if (!$signature) {
                return redirect()->back()->with('error', 'No tienes una firma digital activa. Carga una en "Firma Digital".');
            }

            // Aprobar el entregable
            $approval->approve($signature->id);

            return redirect()->back()
                ->with('success', 'Entregable aprobado y firmado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al aprobar: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar un entregable con feedback
     */
    public function reject(Request $request, $id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $request->validate([
            'feedback' => 'required|string|min:10|max:500',
        ], [
            'feedback.required' => 'Debes escribir una razón para rechazar.',
            'feedback.min' => 'La razón debe tener al menos 10 caracteres.',
            'feedback.max' => 'La razón no puede exceder 500 caracteres.',
        ]);

        $approval = Approval::findOrFail($id);

        // Verificar que el usuario sea el instructor asignado
        if ($approval->instructor_id != $usuario['id']) {
            return redirect()->back()->with('error', 'No tienes permiso para rechazar este entregable.');
        }

        try {
            // Rechazar el entregable con feedback
            $approval->reject($request->input('feedback'));

            return redirect()->back()
                ->with('info', 'Entregable rechazado. Se notificará al colaborador.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al rechazar: ' . $e->getMessage());
        }
    }

    /**
     * Crear un entregable para pruebas (simulación)
     */
    public function createMock(Request $request)
    {
        $usuario = session('sisgedi_user');

        Approval::create([
            'deliverable_id' => rand(1, 1000),
            'deliverable_name' => 'Informe de Gestión Q3',
            'colaborador_id' => 1,
            'colaborador_name' => 'Juan Rodríguez',
            'instructor_id' => $usuario['id'],
            'instructor_name' => $usuario['nombre'],
            'status' => 'pendiente',
            'file_path' => 'uploads/mock_file.pdf',
            'file_type' => 'PDF',
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Entregable de prueba creado.');
    }
}
