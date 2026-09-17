<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentoGuia extends Model
{
    protected $table = 'documentos_guia';
    protected $primaryKey = 'documento_guia_id';

    protected $fillable = [
        'tarea_id',
        'instrucciones',
        'entregables_esperados',
        'plazos',
        'archivo_url',
    ];
}
