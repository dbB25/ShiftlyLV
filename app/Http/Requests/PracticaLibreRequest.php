<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Avance de la práctica libre en /ejercicios.
 */
class PracticaLibreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unidad' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/'],
            'completados' => ['required', 'integer', 'min:0', 'max:1000'],
            'total' => ['required', 'integer', 'min:0', 'max:1000', 'gte:completados'],
        ];
    }
}
