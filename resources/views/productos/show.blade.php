<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del producto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .contenedor {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #263238;
            margin-bottom: 25px;
        }

        .dato {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .dato strong {
            display: inline-block;
            width: 150px;
            color: #475569;
        }

        .boton {
            display: inline-block;
            padding: 11px 16px;
            margin-top: 22px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .volver {
            background: #64748b;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h1>Detalles del producto</h1>

        <div class="dato">
            <strong>ID:</strong>
            {{ $producto->id }}
        </div>

        <div class="dato">
            <strong>Nombre:</strong>
            {{ $producto->nombre }}
        </div>

        <div class="dato">
            <strong>Precio:</strong>
            ${{ number_format($producto->precio, 2) }}
        </div>

        <div class="dato">
            <strong>Stock:</strong>
            {{ $producto->stock }}
        </div>

        <div class="dato">
            <strong>Fecha de registro:</strong>
            {{ $producto->created_at->format('d/m/Y H:i') }}
        </div>

        <a href="{{ route('productos.edit', $producto) }}" class="boton">
            Editar producto
        </a>

        <a href="{{ route('productos.index') }}" class="boton volver">
            Regresar
        </a>

    </div>

</body>

</html>
