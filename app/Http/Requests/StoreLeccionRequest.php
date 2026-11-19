<?php

namespace App\Http\Requests;

use App\Models\Leccion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida los datos para crear una lección.
 */
class StoreLeccionRequest extends FormRequest
{
    public const NIVELES = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->reglas();
    }

    /**
     * Reglas compartidas con UpdateLeccionRequest. Al editar se pasa la
     * lección actual para que "unique" la ignore.
     */
    protected function reglas(?Leccion $leccionActual = null): array
    {
        $tituloUnico = Rule::unique('lecciones', 'titulo')
            ->where('area_id', $this->input('area_id'));

        if ($leccionActual) {
            $tituloUnico->ignore($leccionActual);
        }

        return [
            'area_id' => ['required', 'integer', 'exists:areas,id'],
            'titulo' => ['required', 'string', 'max:150', $tituloUnico],
            'nivel' => ['required', Rule::in(self::NIVELES)],
            'orden' => ['required', 'integer', 'min:1', 'max:999'],
            'publicada' => ['boolean'],
        ];
    }

    /**
     * Un checkbox sin marcar no se envía; se convierte en false.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['publicada' => $this->boolean('publicada')]);
    }

    public function messages(): array
    {
        return [
            'area_id.required' => 'Selecciona el área de la lección.',
            'area_id.integer' => 'Selecciona un área válida.',
            'area_id.exists' => 'El área seleccionada no existe.',
            'titulo.required' => 'Escribe el título de la lección.',
            'titulo.max' => 'El título no puede tener más de :max caracteres.',
            'titulo.unique' => 'Ya existe una lección con ese título en el área seleccionada.',
            'nivel.required' => 'Selecciona el nivel de la lección.',
            'nivel.in' => 'El nivel debe ser A1, A2, B1, B2, C1 o C2.',
            'orden.required' => 'Escribe el orden de la lección.',
            'orden.integer' => 'El orden debe ser un número entero.',
            'orden.min' => 'El orden debe ser al menos :min.',
            'orden.max' => 'El orden no puede ser mayor que :max.',
            'publicada.boolean' => 'El estado de publicación no es válido.',
        ];
    }
}
