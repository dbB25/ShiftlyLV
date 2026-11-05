<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Panel del Usuario</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Tu CSS personalizado desde public/css/dashboard.css --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

    <main class="container py-4">
        <h1 class="text-center mb-4">Panel del Usuario</h1>

        <div class="row g-4">
            {{-- Navegación Rápida --}}
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="card-title">Navegación Rápida</h5>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">Inicio</a>
                        <a href="#" class="btn btn-primary">Ir a Ruta de Aprendizaje</a>
                        <a href="#" class="btn btn-outline-primary">Ver Últimos Resultados</a>
                        <a href="#" class="btn btn-outline-primary">Consultar Glosario</a>
                    </div>
                </div>
            </div>

            {{-- Editar Perfil --}}
            <div class="col-md-8">
                <div class="card p-4 shadow-sm">
                    <h5 class="card-title">Editar Perfil de Usuario</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" placeholder="Mínimo 3 caracteres">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" placeholder="ejemplo@correo.com">
                        </div>
                        <button type="button" class="btn btn-primary w-100">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- Tu JS personalizado desde public/js/dashboard.js --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>