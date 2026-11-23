<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un intento del examen diagnóstico.
 */
class Diagnostico extends Model
{
    protected $fillable = [
        'user_id',
        'nivel',
        'carrera',
        'aciertos',
        'total',
        'resultado',
        'realizado_en',
    ];

    protected function casts(): array
    {
        return [
            'aciertos' => 'integer',
            'total' => 'integer',
            'resultado' => 'array',
            'realizado_en' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
