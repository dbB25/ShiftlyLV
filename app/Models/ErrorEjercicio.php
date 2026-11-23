<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ejercicio fallado que el alumno puede repasar después.
 */
class ErrorEjercicio extends Model
{
    protected $table = 'errores_ejercicios';

    protected $fillable = [
        'progreso_ruta_id',
        'leccion_clave',
        'leccion_titulo',
        'clave',
        'clave_hash',
        'ejercicio',
        'fallado_en',
    ];

    protected function casts(): array
    {
        return [
            'ejercicio' => 'array',
            'fallado_en' => 'datetime',
        ];
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(ProgresoRuta::class, 'progreso_ruta_id');
    }
}
