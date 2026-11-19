<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leccion extends Model
{
    use HasFactory;

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

    /**
     * Filtra por título sin distinguir mayúsculas (ILIKE en PostgreSQL).
     */
    public function scopeBuscar(Builder $consulta, ?string $texto): Builder
    {
        return blank($texto) ? $consulta : $consulta->whereLike('titulo', "%{$texto}%");
    }
}
