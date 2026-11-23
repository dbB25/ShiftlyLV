<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Avance en la práctica libre de /ejercicios.
 */
class PracticaLibre extends Model
{
    protected $table = 'practicas_libres';

    protected $fillable = [
        'user_id',
        'unidad',
        'completados',
        'total',
        'porcentaje',
        'completado',
    ];

    protected function casts(): array
    {
        return [
            'completados' => 'integer',
            'total' => 'integer',
            'porcentaje' => 'integer',
            'completado' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
