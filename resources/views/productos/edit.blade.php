<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>

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
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button,
        .volver {
            display: inline-block;
            padding: 11px 16px;
            margin-top: 22px;
            border: none;
            border-radius: 5px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .volver {
            background: #64748b;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h1>Editar producto</h1>

        <form action="{{ route('productos.update', $producto) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nombre">Nombre del producto:</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
            @error('nombre')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0"
                value="{{ old('precio', $producto->precio) }}" required>
            @error('precio')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="stock">Cantidad en existencia:</label>
            <input type="number" id="stock" name="stock" min="0"
                value="{{ old('stock', $producto->stock) }}" required>
            @error('stock')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">Actualizar producto</button>

            <a href="{{ route('productos.index') }}" class="volver">
                Regresar
            </a>
        </form>

    </div>

</body>

</html>
