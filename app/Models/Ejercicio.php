<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    protected $fillable = [
        'leccion_id',
        'titulo',
        'tipo',
        'pregunta',
        'opciones',
        'respuesta',
        'respuesta_correcta',
        'retroalimentacion',
    ];

    protected function casts(): array
    {
        return [
            'opciones' => 'array',
        ];
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
