<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $fillable = [
        'instructor_id',
        'instructor_name',
        'file_path',
        'file_type',
        'file_size',
        'version',
        'is_active',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Obtener todas las versiones de un instructor
     */
    public static function getVersions($instructor_id)
    {
        return self::where('instructor_id', $instructor_id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Obtener la firma activa de un instructor
     */
    public static function getActive($instructor_id)
    {
        return self::where('instructor_id', $instructor_id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Obtener el número de versión siguiente
     */
    public static function getNextVersion($instructor_id)
    {
        $lastVersion = self::where('instructor_id', $instructor_id)
            ->latest('created_at')
            ->first();

        if (!$lastVersion) {
            return 'v1';
        }

        preg_match('/v(\d+)/', $lastVersion->version, $matches);
        $number = isset($matches[1]) ? intval($matches[1]) + 1 : 2;

        return 'v' . $number;
    }

    /**
     * Obtener el contador de documentos firmados
     */
    public function getSignedDocumentsCount()
    {
        return Approval::where('signature_id', $this->id)
            ->where('status', 'aprobado')
            ->count();
    }
}
