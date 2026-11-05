<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" class="logo-shiftly">
                <span>Shiftly</span>
            </a>
            <div class="navbar-links">
                <a href="{{ url('/login') }}">Iniciar Sesión</a>
            </div>
        </div>
    </header>

    <main class="vista-autenticacion">
        <div class="contenedor-autenticacion">
            
            <div class="tarjeta-autenticacion">
                <div class="encabezado-auth">
                    <p class="etiqueta-resultado">EMPIEZA HOY</p>
                    <h1>Crear Cuenta</h1>
                    <p class="subtitulo-resultado">Únete a Shiftly y potencia tu inglés técnico.</p>
                </div>

                <form class="formulario-auth" id="formularioRegistro" method="POST" action="{{ route('registro.registrar') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="campo-auth">
                        <label for="nombre" class="label-auth">Nombre Completo</label>
                        <div class="caja-input">
                            <i class="bi bi-person icon-input"></i>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="input-auth" placeholder="Tu nombre" required>
                        </div>
                    </div>

                    <div class="campo-auth">
                        <label for="email" class="label-auth">Correo Electrónico</label>
                        <div class="caja-input">
                            <i class="bi bi-envelope icon-input"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-auth" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>

                    <div class="campo-auth">
                        <label for="password" class="label-auth">Contraseña</label>
                        <div class="caja-input">
                            <i class="bi bi-lock icon-input"></i>
                            <input type="password" id="password" name="password" class="input-auth" placeholder="Mínimo 8 caracteres" required>
                            
                            <button type="button" id="mostrarPassword" class="btn border-0" aria-label="Mostrar contraseña" aria-pressed="false">
                                <i class="bi bi-eye" id="iconoPassword"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-resultado btn-siguiente w-100 mt-2">Crear Mi Cuenta</button>
                </form>

                <div class="pie-auth">
                    <p class="text-muted">
                        ¿Ya tienes una cuenta? 
                        <a href="{{ url('/login') }}" class="enlace-primario">Inicia sesión</a>
                    </p>
                </div>
            </div>

        </div>
    </main>

    <footer class="footer-simple">
        <div class="container text-center">
            <p class="mb-0 text-muted small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/registro.js') }}"></script>
</body>

</html>