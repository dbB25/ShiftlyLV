<?php

namespace App\Http\Requests;

/**
 * Valida los datos para editar un ejercicio. Usa las mismas reglas que al
 * crear, pero el título único ignora el ejercicio que se está editando.
 */
class UpdateEjercicioRequest extends StoreEjercicioRequest
{
    public function rules(): array
    {
        return $this->reglas($this->route('ejercicio'));
    }
}
