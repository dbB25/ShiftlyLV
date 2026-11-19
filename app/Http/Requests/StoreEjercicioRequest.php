<?php

namespace App\Http\Requests;

use App\Models\Ejercicio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Valida los datos para crear un ejercicio.
 */
class StoreEjercicioRequest extends FormRequest
{
    public const TIPOS = ['completar', 'opcion_multiple', 'ordenar', 'emparejar'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->reglas();
    }

    /**
     * Reglas compartidas con UpdateEjercicioRequest. Al editar se pasa el
     * ejercicio actual para que "unique" lo ignore.
     */
    protected function reglas(?Ejercicio $ejercicioActual = null): array
    {
        $tituloUnico = Rule::unique('ejercicios', 'titulo')
            ->where('leccion_id', $this->input('leccion_id'));

        if ($ejercicioActual) {
            $tituloUnico->ignore($ejercicioActual);
        }

        return [
            'leccion_id' => ['required', 'integer', 'exists:lecciones,id'],
            'titulo' => ['required', 'string', 'max:150', $tituloUnico],
            'tipo' => ['required', Rule::in(self::TIPOS)],
            'pregunta' => ['required', 'string', 'max:2000'],
            'opciones' => ['nullable', 'string', 'max:5000'],
            'respuesta_correcta' => ['nullable', 'required_unless:tipo,emparejar', 'string', 'max:1000'],
            'retroalimentacion' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'leccion_id.required' => 'Selecciona la lección a la que pertenece el ejercicio.',
            'leccion_id.integer' => 'Selecciona una lección válida.',
            'leccion_id.exists' => 'La lección seleccionada no existe.',
            'titulo.required' => 'Escribe un título para el ejercicio.',
            'titulo.max' => 'El título no puede tener más de :max caracteres.',
            'titulo.unique' => 'Ya existe un ejercicio con ese título en la lección seleccionada.',
            'tipo.required' => 'Selecciona el tipo de ejercicio.',
            'tipo.in' => 'Selecciona un tipo de ejercicio válido.',
            'pregunta.required' => 'Escribe la consigna o pregunta.',
            'pregunta.max' => 'La pregunta no puede tener más de :max caracteres.',
            'opciones.max' => 'Las opciones no pueden tener más de :max caracteres.',
            'respuesta_correcta.required_unless' => 'Escribe la respuesta correcta para este tipo de ejercicio.',
            'respuesta_correcta.max' => 'La respuesta no puede tener más de :max caracteres.',
            'retroalimentacion.max' => 'La retroalimentación no puede tener más de :max caracteres.',
        ];
    }

    /**
     * Reglas que dependen de varios campos a la vez (opciones según el tipo).
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $opciones = $this->opcionesLimpias();

                if ($this->input('tipo') === 'opcion_multiple') {
                    $respuesta = Str::lower(trim((string) $this->input('respuesta_correcta')));
                    $incluida = collect($opciones)->contains(fn ($opcion) => Str::lower($opcion) === $respuesta);

                    if (count($opciones) < 2 || ! $incluida) {
                        $validator->errors()->add(
                            'opciones',
                            'Agrega al menos dos opciones e incluye entre ellas la respuesta correcta.'
                        );
                    }
                }

                if ($this->input('tipo') === 'emparejar') {
                    $paresValidos = count($opciones) >= 2 && collect($opciones)->every(function ($opcion) {
                        $partes = explode('=', $opcion, 2);

                        return count($partes) === 2 && trim($partes[0]) !== '' && trim($partes[1]) !== '';
                    });

                    if (! $paresValidos) {
                        $validator->errors()->add(
                            'opciones',
                            'Escribe al menos dos pares, uno por línea, con el formato inglés = español.'
                        );
                    }
                }
            },
        ];
    }

    /**
     * Datos listos para guardar: las opciones pasan de texto (una por línea) a arreglo.
     */
    public function datosEjercicio(): array
    {
        $datos = $this->validated();
        $opciones = $this->opcionesLimpias();

        $datos['opciones'] = $opciones ?: null;
        $datos['respuesta_correcta'] = $datos['respuesta_correcta'] ?? null;
        $datos['retroalimentacion'] = $datos['retroalimentacion'] ?? null;

        return $datos;
    }

    private function opcionesLimpias(): array
    {
        $lineas = preg_split('/\R/u', (string) $this->input('opciones', '')) ?: [];

        return array_values(array_filter(array_map('trim', $lineas), fn ($linea) => $linea !== ''));
    }
}
