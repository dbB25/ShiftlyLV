<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Estado completo de una ruta enviado por el navegador al completar
 * una lección, terminar ejercicios, fallar un reto o reiniciar la ruta.
 */
class GuardarRutaRequest extends FormRequest
{
    /** IDs de lección: "sistemas-01" (catálogo) o "p-12" (personalizada). */
    public const PATRON_LECCION = '/^[a-z0-9][a-z0-9-]{0,59}$/';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $leccion = ['string', 'regex:'.self::PATRON_LECCION];

        return [
            'completadas' => ['present', 'array', 'max:200'],
            'completadas.*' => [...$leccion, 'distinct'],

            'xp' => ['nullable', 'array', 'max:200'],
            'xp.*' => ['integer', 'min:0', 'max:1000'],

            'ejercicios' => ['nullable', 'array', 'max:200'],
            'ejercicios.*' => ['array'],
            'ejercicios.*.aciertosPrimerIntento' => ['nullable', 'integer', 'min:0', 'max:500'],
            'ejercicios.*.total' => ['nullable', 'integer', 'min:0', 'max:500'],
            'ejercicios.*.vidas' => ['nullable', 'integer', 'min:0', 'max:10'],
            'ejercicios.*.duracionSeg' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'ejercicios.*.fuente' => ['nullable', 'string', 'max:20'],
            'ejercicios.*.fecha' => ['nullable', 'date'],

            'errores' => ['nullable', 'array', 'max:60'],
            'errores.*.clave' => ['required', 'string', 'max:2000'],
            'errores.*.leccionId' => ['required', ...$leccion],
            'errores.*.leccionTitulo' => ['nullable', 'string', 'max:200'],
            'errores.*.ejercicio' => ['required', 'array'],
            'errores.*.fecha' => ['nullable', 'date'],

            'ubicacion' => ['nullable', 'array'],
            'ubicacion.numero' => ['nullable', 'integer', 'min:1', 'max:100'],
            'ubicacion.fecha' => ['nullable', 'date'],
        ];
    }

    /** Las llaves de "xp" y "ejercicios" también son IDs de lección. */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                foreach (['xp', 'ejercicios'] as $campo) {
                    foreach (array_keys((array) $this->input($campo, [])) as $clave) {
                        if (! preg_match(self::PATRON_LECCION, (string) $clave)) {
                            $validator->errors()->add($campo, "La lección «{$clave}» no es válida.");
                        }
                    }
                }
            },
        ];
    }
}
