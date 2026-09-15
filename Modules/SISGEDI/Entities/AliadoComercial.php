<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class AliadoComercial extends Model
{
    // Configuración de la tabla
    protected $table = 'aliado_comercial';
    protected $primaryKey = 'aliado_id';
    
    // Desactivar timestamps automáticos ya que la tabla SQL no tiene created_at / updated_at
    public $timestamps = false;

    // Campos que se pueden insertar masivamente
    protected $fillable = [
        'nombre',
        'tipo',
        'datos_contacto',
        'condiciones',
        'estado',
        'motivo_rechazo',
        'registrado_por'
    ];

    // Relación: Un aliado comercial tiene muchos productos
    public function productos()
    {
        return $this->hasMany(ProductoPortafolio::class, 'aliado_id', 'aliado_id');
    }
}
