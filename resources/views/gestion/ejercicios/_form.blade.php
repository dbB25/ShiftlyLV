{{-- Formulario compartido por create y edit. Recibe $ejercicio y $lecciones. --}}
@php
    $opcionesTexto = old('opciones', implode("\n", $ejercicio->opciones ?? []));
    $tipos = [
        'completar' => 'Completar la frase',
        'opcion_multiple' => 'Opción múltiple',
        'ordenar' => 'Ordenar palabras',
        'emparejar' => 'Emparejar',
    ];
@endphp

<p class="small text-muted">Los campos con <span class="campo-obligatorio">*</span> son obligatorios.</p>

<div class="mb-3">
    <label class="form-label" for="leccion_id">Lección <span class="campo-obligatorio">*</span></label>
    <select class="form-select @error('leccion_id') is-invalid @enderror" id="leccion_id" name="leccion_id" required>
        <option value="">Selecciona una lección</option>
        @foreach ($lecciones as $leccion)
            <option value="{{ $leccion->id }}" @selected((string) old('leccion_id', $ejercicio->leccion_id) === (string) $leccion->id)>
                {{ $leccion->titulo }}
            </option>
        @endforeach
    </select>
    @error('leccion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="titulo">Título <span class="campo-obligatorio">*</span></label>
    <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo"
        value="{{ old('titulo', $ejercicio->titulo) }}" maxlength="150" required>
    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="tipo">Tipo de ejercicio <span class="campo-obligatorio">*</span></label>
    <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
        @foreach ($tipos as $valor => $nombre)
            <option value="{{ $valor }}" @selected(old('tipo', $ejercicio->tipo) === $valor)>{{ $nombre }}</option>
        @endforeach
    </select>
    @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="pregunta">Consigna o pregunta <span class="campo-obligatorio">*</span></label>
    <textarea class="form-control @error('pregunta') is-invalid @enderror" id="pregunta" name="pregunta" rows="3"
        maxlength="2000" required>{{ old('pregunta', $ejercicio->pregunta) }}</textarea>
    @error('pregunta')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="opciones">Opciones o pares</label>
    <textarea class="form-control @error('opciones') is-invalid @enderror" id="opciones" name="opciones" rows="4"
        maxlength="5000" aria-describedby="ayuda-opciones">{{ $opcionesTexto }}</textarea>
    <div class="form-text" id="ayuda-opciones">
        Una opción por línea. Obligatorio en opción múltiple y emparejar (formato «inglés = español»).
    </div>
    @error('opciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="respuesta_correcta">Respuesta correcta</label>
    <input type="text" class="form-control @error('respuesta_correcta') is-invalid @enderror" id="respuesta_correcta"
        name="respuesta_correcta" value="{{ old('respuesta_correcta', $ejercicio->respuesta_correcta) }}" maxlength="1000"
        aria-describedby="ayuda-respuesta">
    <div class="form-text" id="ayuda-respuesta">
        Obligatoria excepto en emparejar. En opción múltiple debe coincidir con una de las opciones.
    </div>
    @error('respuesta_correcta')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label" for="retroalimentacion">Retroalimentación</label>
    <textarea class="form-control @error('retroalimentacion') is-invalid @enderror" id="retroalimentacion"
        name="retroalimentacion" rows="2" maxlength="1000">{{ old('retroalimentacion', $ejercicio->retroalimentacion) }}</textarea>
    @error('retroalimentacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
