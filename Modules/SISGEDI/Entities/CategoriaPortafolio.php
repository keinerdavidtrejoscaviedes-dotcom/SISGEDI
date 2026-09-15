<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoriaPortafolio extends Model
{
    // Configuración de la tabla
    protected $table = 'categoria_portafolio';
    protected $primaryKey = 'categoria_portafolio_id';
    
    // Desactivar timestamps automáticos ya que la tabla SQL no tiene created_at / updated_at
    public $timestamps = false;

    // Campos que se pueden insertar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    // Relación: Una categoría tiene muchos productos
    public function productos()
    {
        return $this->hasMany(ProductoPortafolio::class, 'categoria_portafolio_id', 'categoria_portafolio_id');
    }
}
