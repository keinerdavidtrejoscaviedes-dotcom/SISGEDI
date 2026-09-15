<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductoPortafolio extends Model
{
    // Configuración de la tabla
    protected $table = 'producto_portafolio';
    protected $primaryKey = 'producto_id';
    
    // Esta tabla sí tiene timestamps (created_at, updated_at) en SQL,
    // así que mantenemos el comportamiento por defecto de Laravel
    public $timestamps = true;

    // Campos que se pueden insertar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_portafolio_id',
        'aliado_id',
        'estado',
        'registrado_por'
    ];

    // Relación: Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaPortafolio::class, 'categoria_portafolio_id', 'categoria_portafolio_id');
    }

    // Relación: Un producto puede pertenecer a un aliado comercial
    public function aliado()
    {
        return $this->belongsTo(AliadoComercial::class, 'aliado_id', 'aliado_id');
    }
}
