<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leccion extends Model
{
    protected $table = 'lecciones';

    protected $fillable = [
        'area_id',
        'titulo',
        'nivel',
        'orden',
        'publicada',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'publicada' => 'boolean',
        ];
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class);
    }

    public function progresos(): HasMany
    {
        return $this->hasMany(Progreso::class);
    }
}
