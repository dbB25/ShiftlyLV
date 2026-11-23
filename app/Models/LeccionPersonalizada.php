<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Lección que el alumno crea al final del árbol de su carrera.
 */
class LeccionPersonalizada extends Model
{
    protected $table = 'lecciones_personalizadas';

    protected $fillable = [
        'user_id',
        'carrera',
        'titulo',
        'descripcion',
        'vocabulario',
        'xp',
        'forma',
        'icono',
    ];

    protected function casts(): array
    {
        return [
            'vocabulario' => 'array',
            'xp' => 'integer',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** ID que usa el árbol de lecciones en JavaScript ("p-12"). */
    public function claveArbol(): string
    {
        return 'p-'.$this->id;
    }

    /** Formato que esperan ruta-*.js y ejercicios-leccion.js. */
    public function paraNavegador(): array
    {
        return [
            'id' => $this->claveArbol(),
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion ?? '',
            'vocab' => $this->vocabulario ?? [],
            'xp' => $this->xp,
            'forma' => $this->forma,
            'icono' => $this->icono,
            'creado' => $this->created_at?->toIso8601String(),
            'editado' => $this->updated_at?->toIso8601String(),
        ];
    }
}
