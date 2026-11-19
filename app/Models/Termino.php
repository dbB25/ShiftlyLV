<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Termino extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'termino',
        'traduccion',
        'ejemplo',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
