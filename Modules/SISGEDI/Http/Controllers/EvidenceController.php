<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\Evidence;
use Modules\SISGEDI\Entities\Approval;

class EvidenceController extends Controller
{
    /**
     * Cargar evidencia para una tarea
     */
    public function upload(Request $request, $approval_id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        // Validar que el archivo existe
        $request->validate([
            'evidence_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip|max:10240',
            'description' => 'nullable|string|max:500',
        ], [
            'evidence_file.required' => 'Debes seleccionar un archivo.',
            'evidence_file.file' => 'El archivo debe ser válido.',
            'evidence_file.mimes' => 'El archivo debe ser: PDF, Word, Excel, PowerPoint, Imagen o ZIP.',
            'evidence_file.max' => 'El archivo no puede exceder 10MB.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
        ]);

        try {
            // Obtener el approval
            $approval = Approval::findOrFail($approval_id);

            // Verificar que el usuario sea el colaborador asignado
            if ($approval->colaborador_id != $usuario['id']) {
                return redirect()->back()->with('error', 'No tienes permiso para cargar evidencia en este entregable.');
            }

            // Guardar el archivo
            $file = $request->file('evidence_file');
            $colaborador_id = $usuario['id'];
            $timestamp = time();
            $filename = 'evidence_' . $approval_id . '_' . $colaborador_id . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('evidences', $filename, 'public');

            // Crear registro de evidencia
            $evidence = Evidence::create([
                'approval_id' => $approval_id,
                'colaborador_id' => $colaborador_id,
                'file_path' => 'storage/' . $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => round($file->getSize() / 1024), // En KB
                'description' => $request->input('description'),
                'uploaded_at' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Evidencia cargada exitosamente. Archivo: ' . $file->getClientOriginalName());

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar la evidencia: ' . $e->getMessage());
        }
    }

    /**
     * Descargar una evidencia
     */
    public function download($id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $evidence = Evidence::findOrFail($id);
        $approval = $evidence->approval;

        // Verificar permisos: el colaborador que la subió o el instructor asignado
        $isOwner = $evidence->colaborador_id == $usuario['id'];
        $isInstructor = $approval->instructor_id == $usuario['id'];

        if (!$isOwner && !$isInstructor) {
            return redirect()->back()->with('error', 'No tienes permiso para descargar esta evidencia.');
        }

        return response()->download(public_path($evidence->file_path), $evidence->file_name);
    }

    /**
     * Eliminar una evidencia
     */
    public function delete($id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $evidence = Evidence::findOrFail($id);

        // Verificar que el usuario sea el colaborador que la subió
        if ($evidence->colaborador_id != $usuario['id']) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta evidencia.');
        }

        try {
            // Eliminar archivo del servidor
            if (file_exists(public_path($evidence->file_path))) {
                unlink(public_path($evidence->file_path));
            }

            // Eliminar registro
            $evidence->delete();

            return redirect()->back()
                ->with('success', 'Evidencia eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
