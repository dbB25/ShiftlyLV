<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
            'fecha' => 'date',
            'xp' => 'integer',
            'practicas' => 'integer',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
