<?php

namespace App\Http\Requests;

use App\Services\ProgresoUsuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Crear o editar una lección personalizada desde el árbol de una ruta.
 */
class LeccionPersonalizadaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'titulo' => trim((string) $this->input('titulo')),
            'descripcion' => trim((string) $this->input('descripcion')),
        ]);
    }

    public function rules(): array
    {
        return [
            // La carrera solo se elige al crear; al editar la lección no cambia de ruta
            'carrera' => [
                Rule::requiredIf($this->isMethod('post')),
                Rule::in(ProgresoUsuario::CARRERAS),
            ],
            'titulo' => ['required', 'string', 'min:3', 'max:60'],
            'descripcion' => ['nullable', 'string', 'max:240'],
            'vocab' => ['present', 'array', 'max:8'],
            'vocab.*.en' => ['required', 'string', 'max:60'],
            'vocab.*.es' => ['required', 'string', 'max:60'],
            'xp' => ['required', 'integer', 'in:10,20,30,50'],
            'forma' => ['required', 'string', 'max:40', 'regex:/^[a-z0-9-]+$/'],
            'icono' => ['required', 'string', 'max:60', 'regex:/^bi-[a-z0-9-]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'Escribe un título para la lección.',
            'titulo.min' => 'El título debe tener al menos :min caracteres.',
            'titulo.max' => 'El título no puede tener más de :max caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'vocab.max' => 'Agrega como máximo :max términos clave.',
            'vocab.*.en.required' => 'Cada término necesita su palabra en inglés.',
            'vocab.*.es.required' => 'Cada término necesita su traducción.',
            'xp.in' => 'Elige un XP válido.',
        ];
    }
}
