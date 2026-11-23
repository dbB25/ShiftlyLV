<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * XP ganado y número de prácticas de un usuario en un día.
 */
class ActividadDiaria extends Model
{
    protected $table = 'actividad_diaria';

    protected $fillable = [
        'user_id',
        'fecha',
        'xp',
        'practicas',
    ];

    protected function casts(): array
    {
        return [
            'xp' => 'integer',
            'practicas' => 'integer',
        ];
    }

    /**
     * La fecha se guarda siempre como AAAA-MM-DD (sin hora), para que las
     * búsquedas por día coincidan igual en PostgreSQL y en SQLite (pruebas).
     */
    protected function fecha(): Attribute
    {
        return Attribute::make(
            get: fn ($valor) => $valor ? Carbon::parse($valor)->startOfDay() : null,
            set: fn ($valor) => $valor ? Carbon::parse($valor)->toDateString() : null,
        );
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
