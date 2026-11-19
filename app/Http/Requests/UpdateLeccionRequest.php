<?php

namespace App\Http\Requests;

/**
 * Valida los datos para editar una lección. Usa las mismas reglas que al
 * crear, pero el título único ignora la lección que se está editando.
 */
class UpdateLeccionRequest extends StoreLeccionRequest
{
    public function rules(): array
    {
        return $this->reglas($this->route('leccion'));
    }
}
