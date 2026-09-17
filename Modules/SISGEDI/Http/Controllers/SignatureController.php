<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\Signature;
use Modules\SISGEDI\Entities\Approval;

class SignatureController extends Controller
{
    /**
     * Subir una nueva firma digital
     */
    public function upload(Request $request)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $request->validate([
            'signature_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'signature_file.required' => 'Debes seleccionar un archivo de firma.',
            'signature_file.image' => 'El archivo debe ser una imagen.',
            'signature_file.mimes' => 'La firma debe estar en formato PNG, JPG, JPEG o GIF.',
            'signature_file.max' => 'El archivo no puede exceder 2MB.',
        ]);

        try {
            // Obtener datos del usuario de la sesión
            $instructor_id = $usuario['id'];
            $instructor_name = $usuario['nombre'];

            // Inactivar firma anterior si existe
            Signature::where('instructor_id', $instructor_id)
                ->update(['is_active' => false]);

            // Guardar el archivo
            $file = $request->file('signature_file');
            $filename = 'signature_' . $instructor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('signatures', $filename, 'public');

            // Obtener siguiente versión
            $version = Signature::getNextVersion($instructor_id);

            // Crear nuevo registro de firma
            $signature = Signature::create([
                'instructor_id' => $instructor_id,
                'instructor_name' => $instructor_name,
                'file_path' => 'storage/' . $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => round($file->getSize() / 1024), // En KB
                'version' => $version,
                'is_active' => true,
                'uploaded_at' => now(),
            ]);

            return redirect()->route('sisgedi.instructor.gestionar-firma')
                ->with('success', 'Firma digital cargada exitosamente. Versión: ' . $version);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar la firma: ' . $e->getMessage());
        }
    }

    /**
     * Descargar una firma
     */
    public function download($id)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $signature = Signature::findOrFail($id);

        // Verificar que el usuario sea el propietario de la firma
        if ($signature->instructor_id != $usuario['id']) {
            return redirect()->back()->with('error', 'No tienes permiso para descargar esta firma.');
        }

        return response()->download(public_path($signature->file_path));
    }

    /**
     * Reemplazar firma (desactivar anterior y activar nueva)
     */
    public function replace(Request $request)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $request->validate([
            'signature_id' => 'required|exists:signatures,id',
        ]);

        $instructor_id = $usuario['id'];
        $signature_id = $request->input('signature_id');

        // Verificar que la firma pertenece al instructor
        $signature = Signature::findOrFail($signature_id);
        if ($signature->instructor_id != $instructor_id) {
            return redirect()->back()->with('error', 'No tienes permiso para activar esta firma.');
        }

        // Desactivar todas las firmas del instructor
        Signature::where('instructor_id', $instructor_id)
            ->update(['is_active' => false]);

        // Activar la firma seleccionada
        $signature->update(['is_active' => true]);

        return redirect()->route('sisgedi.instructor.gestionar-firma')
            ->with('success', 'Firma ' . $signature->version . ' activada correctamente.');
    }
}
