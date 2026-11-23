<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'apellido', 'carrera', 'area_profesional', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'meta_diaria' => 'integer',
            'mejor_pronunciacion' => 'integer',
        ];
    }

    public function progresos(): HasMany
    {
        return $this->hasMany(Progreso::class);
    }

    public function progresoRutas(): HasMany
    {
        return $this->hasMany(ProgresoRuta::class);
    }

    public function actividadDiaria(): HasMany
    {
        return $this->hasMany(ActividadDiaria::class);
    }

    public function leccionesPersonalizadas(): HasMany
    {
        return $this->hasMany(LeccionPersonalizada::class);
    }

    public function diagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class);
    }

    public function practicasLibres(): HasMany
    {
        return $this->hasMany(PracticaLibre::class);
    }
}
