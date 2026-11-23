<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Resultado del examen diagnóstico enviado por diagnostico.js.
 */
class DiagnosticoRequest extends FormRequest
{
    private const TAMANO_MAXIMO = 50000; // bytes del JSON del resultado

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resultado' => ['required', 'array'],
            'resultado.nivel' => ['required', 'string', 'max:10'],
            'resultado.carrera' => ['nullable', 'string', 'max:80'],
            'resultado.aciertos' => ['required', 'integer', 'min:0', 'max:500'],
            'resultado.total' => ['required', 'integer', 'min:1', 'max:500', 'gte:resultado.aciertos'],
            'resultado.fecha' => ['required', 'date'],
            'resultado.revision' => ['nullable', 'array', 'max:100'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (strlen(json_encode($this->input('resultado'))) > self::TAMANO_MAXIMO) {
                    $validator->errors()->add('resultado', 'El resultado del diagnóstico es demasiado grande.');
                }
            },
        ];
    }
}
