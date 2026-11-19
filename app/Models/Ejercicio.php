<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'leccion_id',
        'titulo',
        'tipo',
        'pregunta',
        'opciones',
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

    /**
     * Filtra por título o pregunta sin distinguir mayúsculas (ILIKE en PostgreSQL).
     */
    public function scopeBuscar(Builder $consulta, ?string $texto): Builder
    {
        if (blank($texto)) {
            return $consulta;
        }

        return $consulta->where(function (Builder $grupo) use ($texto) {
            $grupo->whereLike('titulo', "%{$texto}%")
                ->orWhereLike('pregunta', "%{$texto}%");
        });
    }
}
