{{-- Formulario compartido por create y edit. Recibe $leccion y $areas. --}}
<p class="small text-muted">Los campos con <span class="campo-obligatorio">*</span> son obligatorios.</p>

<div class="mb-3">
    <label class="form-label" for="area_id">Área <span class="campo-obligatorio">*</span></label>
    <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
        <option value="">Selecciona un área</option>
        @foreach ($areas as $area)
            <option value="{{ $area->id }}" @selected((string) old('area_id', $leccion->area_id) === (string) $area->id)>
                {{ $area->nombre }}
            </option>
        @endforeach
    </select>
    @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="titulo">Título <span class="campo-obligatorio">*</span></label>
    <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo"
        value="{{ old('titulo', $leccion->titulo) }}" maxlength="150" required>
    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6">
        <label class="form-label" for="nivel">Nivel <span class="campo-obligatorio">*</span></label>
        <select class="form-select @error('nivel') is-invalid @enderror" id="nivel" name="nivel" required>
            @foreach (\App\Http\Requests\StoreLeccionRequest::NIVELES as $nivel)
                <option value="{{ $nivel }}" @selected(old('nivel', $leccion->nivel) === $nivel)>{{ $nivel }}</option>
            @endforeach
        </select>
        @error('nivel')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label" for="orden">Orden <span class="campo-obligatorio">*</span></label>
        <input type="number" class="form-control @error('orden') is-invalid @enderror" id="orden" name="orden"
            value="{{ old('orden', $leccion->orden) }}" min="1" max="999" step="1" required aria-describedby="ayuda-orden">
        <div class="form-text" id="ayuda-orden">Posición de la lección dentro de su área.</div>
        @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-check form-switch mb-4">
    <input class="form-check-input @error('publicada') is-invalid @enderror" type="checkbox" role="switch"
        id="publicada" name="publicada" value="1" @checked(old('publicada', $leccion->publicada))>
    <label class="form-check-label" for="publicada">Publicada (visible para los estudiantes)</label>
    @error('publicada')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
