<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ruta de una carrera para un usuario (sistemas, medicina, ...).
 */
class ProgresoRuta extends Model
{
    protected $table = 'progreso_rutas';

    protected $fillable = [
        'user_id',
        'carrera',
        'ubicacion_leccion',
        'ubicacion_fecha',
    ];

    protected function casts(): array
    {
        return [
            'ubicacion_leccion' => 'integer',
            'ubicacion_fecha' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lecciones(): HasMany
    {
        return $this->hasMany(ProgresoLeccion::class);
    }

    public function errores(): HasMany
    {
        return $this->hasMany(ErrorEjercicio::class);
    }
}
