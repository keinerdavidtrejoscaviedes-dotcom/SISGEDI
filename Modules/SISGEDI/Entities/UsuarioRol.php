<?php

namespace Modules\SISGEDI\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table = 'sisgedi_usuario_roles';

    protected $fillable = [
        'user_id',
        'sisgedi_rol_id',
        'cargo_id',
        'fase_id',
        'estado',
        'fecha_activacion',
        'fecha_desactivacion',
        'activado_por',
        'activacion_automatica',
    ];

    protected $casts = [
        'fecha_activacion' => 'datetime',
        'fecha_desactivacion' => 'datetime',
        'activacion_automatica' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'sisgedi_rol_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function activadoPor()
    {
        return $this->belongsTo(User::class, 'activado_por');
    }

    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeConRol($query, string $nombreRol)
    {
        return $query->whereHas('rol', fn ($q) => $q->where('nombre_rol', $nombreRol));
    }

    /**
     * Busca la asignacion de rol activa de un usuario para un rol y fase dados.
     */
    public static function activaPara(int $userId, string $nombreRol, ?int $faseId = null): ?self
    {
        return static::query()
            ->where('user_id', $userId)
            ->activo()
            ->conRol($nombreRol)
            ->when($faseId, fn ($q) => $q->where('fase_id', $faseId))
            ->with(['cargo.gerencia', 'fase'])
            ->first();
    }
}
