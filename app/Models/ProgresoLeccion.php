<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Avance del usuario en una lección: completada, XP y último resultado de ejercicios.
 */
class ProgresoLeccion extends Model
{
    protected $table = 'progreso_lecciones';

    protected $fillable = [
        'progreso_ruta_id',
        'leccion_clave',
        'completada',
        'orden',
        'xp',
        'aciertos_primer_intento',
        'total_ejercicios',
        'vidas',
        'duracion_seg',
        'fuente',
        'practicada_en',
    ];

    protected function casts(): array
    {
        return [
            'completada' => 'boolean',
            'orden' => 'integer',
            'xp' => 'integer',
            'aciertos_primer_intento' => 'integer',
            'total_ejercicios' => 'integer',
            'vidas' => 'integer',
            'duracion_seg' => 'integer',
            'practicada_en' => 'datetime',
        ];
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(ProgresoRuta::class, 'progreso_ruta_id');
    }
}
