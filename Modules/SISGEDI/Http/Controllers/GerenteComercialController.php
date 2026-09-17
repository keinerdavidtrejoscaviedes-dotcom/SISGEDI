<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISGEDI\Entities\ProductoPortafolio;
use Modules\SISGEDI\Entities\CategoriaPortafolio;
use Modules\SISGEDI\Entities\AliadoComercial;

class GerenteComercialController extends Controller
{
    public function dasboardComercial()
    {
        if (! session('sisgedi_user')) {
            return redirect()->route('sisgedi.login')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        return view('sisgedi::gerente_comercial.dashboard');
    }

    public function portafolioProducto()
    {
        // Cargar productos con sus relaciones
        $productosRaw = ProductoPortafolio::with(['categoria', 'aliado'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Formatear al JSON exacto que espera Alpine.js en la vista
        $productos = $productosRaw->map(function($p) {
            return [
                'id' => $p->producto_id,
                'nombre' => $p->nombre,
                'categoria' => $p->categoria ? $p->categoria->nombre : 'Sin categoría',
                'precio' => (float)$p->precio,
                'descripcion' => $p->descripcion,
                'origen' => $p->aliado_id ? $this->getOrigenString($p->aliado->tipo) : 'Producción propia',
                'aliado' => $p->aliado ? $p->aliado->nombre : '',
                'estado' => ucfirst($p->estado) // Activo, Inactivo
            ];
        });

        $categorias = CategoriaPortafolio::where('estado', 'activa')->get();

        return view('sisgedi::gerente_comercial.productos.producto', compact('productos', 'categorias'));
    }

    public function storeProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'origen' => 'required|string',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        $userId = session('sisgedi_user')['id_users'] ?? 1; // Fallback temporal

        // 1. Obtener o crear Categoría
        $categoria = CategoriaPortafolio::firstOrCreate(
            ['nombre' => $request->categoria],
            ['estado' => 'activa']
        );

        // 2. Obtener o crear Aliado si aplica
        $aliadoId = null;
        if ($request->origen !== 'Producción propia' && !empty($request->aliado)) {
            $tipoDB = $this->getTipoAliadoDB($request->origen);
            
            $aliado = AliadoComercial::firstOrCreate(
                ['nombre' => $request->aliado],
                [
                    'tipo' => $tipoDB,
                    'datos_contacto' => 'Sin datos',
                    'estado' => 'aprobado',
                    'registrado_por' => $userId
                ]
            );
            $aliadoId = $aliado->aliado_id;
        }

        // 3. Crear el Producto
        $producto = ProductoPortafolio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_portafolio_id' => $categoria->categoria_portafolio_id,
            'aliado_id' => $aliadoId,
            'estado' => strtolower($request->estado ?? 'activo'),
            'registrado_por' => $userId
        ]);

        // Retornar en el formato de Alpine.js
        return response()->json([
            'success' => true,
            'producto' => [
                'id' => $producto->producto_id,
                'nombre' => $producto->nombre,
                'categoria' => $categoria->nombre,
                'precio' => (float)$producto->precio,
                'descripcion' => $producto->descripcion,
                'origen' => $request->origen,
                'aliado' => $request->aliado,
                'estado' => ucfirst($producto->estado)
            ]
        ]);
    }

    public function updateProducto(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'origen' => 'required|string',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        $producto = ProductoPortafolio::findOrFail($id);
        $userId = session('sisgedi_user')['id_users'] ?? 1;

        // Categoría
        $categoria = CategoriaPortafolio::firstOrCreate(
            ['nombre' => $request->categoria],
            ['estado' => 'activa']
        );

        // Aliado
        $aliadoId = null;
        if ($request->origen !== 'Producción propia' && !empty($request->aliado)) {
            $tipoDB = $this->getTipoAliadoDB($request->origen);
            $aliado = AliadoComercial::firstOrCreate(
                ['nombre' => $request->aliado],
                [
                    'tipo' => $tipoDB,
                    'datos_contacto' => 'Sin datos',
                    'estado' => 'aprobado',
                    'registrado_por' => $userId
                ]
            );
            $aliadoId = $aliado->aliado_id;
        }

        // Actualizar
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria_portafolio_id' => $categoria->categoria_portafolio_id,
            'aliado_id' => $aliadoId,
            'estado' => strtolower($request->estado ?? 'activo'),
        ]);

        return response()->json([
            'success' => true,
            'producto' => [
                'id' => $producto->producto_id,
                'nombre' => $producto->nombre,
                'categoria' => $categoria->nombre,
                'precio' => (float)$producto->precio,
                'descripcion' => $producto->descripcion,
                'origen' => $request->origen,
                'aliado' => $request->aliado,
                'estado' => ucfirst($producto->estado)
            ]
        ]);
    }

    public function destroyProducto($id)
    {
        $producto = ProductoPortafolio::findOrFail($id);
        $producto->update(['estado' => 'inactivo']);

        return response()->json(['success' => true]);
    }

    public function activarProducto($id)
    {
        $producto = ProductoPortafolio::findOrFail($id);
        $producto->update(['estado' => 'activo']);

        return response()->json(['success' => true]);
    }

    // --- Funciones auxiliares para mapear ENUMS ---
    
    private function getOrigenString($tipoEnum)
    {
        $map = [
            'emprendedor' => 'Emprendedor',
            'egresado' => 'Egresado',
            'asociacion_campesina' => 'Asociación campesina',
            'talento_sena' => 'Talento SENA'
        ];
        return $map[$tipoEnum] ?? 'Otro';
    }

    private function getTipoAliadoDB($origenString)
    {
        $map = [
            'Emprendedor' => 'emprendedor',
            'Egresado' => 'egresado',
            'Asociación campesina' => 'asociacion_campesina',
            'Talento SENA' => 'talento_sena'
        ];
        return $map[$origenString] ?? 'otro';
    }
}
