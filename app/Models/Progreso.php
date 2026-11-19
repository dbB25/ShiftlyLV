<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leccion_id',
        'puntaje',
        'completado',
    ];

    protected function casts(): array
    {
        return [
            'puntaje' => 'decimal:2',
            'completado' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
