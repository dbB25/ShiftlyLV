<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Actividad diaria (racha y meta del día) enviada por shiftly-progreso.js.
 */
class GuardarActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dias' => ['nullable', 'array', 'max:400'],
            'dias.*.xp' => ['required', 'integer', 'min:0', 'max:100000'],
            'dias.*.practicas' => ['required', 'integer', 'min:0', 'max:10000'],
            'meta' => ['nullable', 'integer', 'in:10,20,30,50,100'],
            'mejorPronunciacion' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    /** Cada llave de "dias" debe ser una fecha AAAA-MM-DD. */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                foreach (array_keys((array) $this->input('dias', [])) as $fecha) {
                    $partes = explode('-', (string) $fecha);
                    $valida = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $fecha)
                        && checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0]);
                    if (! $valida) {
                        $validator->errors()->add('dias', "La fecha «{$fecha}» no es válida.");
                    }
                }
            },
        ];
    }
}
