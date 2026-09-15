<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentoGuia extends Model
{
    protected $table = 'sisgedi_documentos_guia';

    protected $fillable = [
        'tarea_id',
        'instrucciones',
        'entregables_esperados',
        'plazos',
        'archivo_url',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }
}
