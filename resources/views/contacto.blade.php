<form action="{{ route('contacto.store') }}" method="POST">
    @csrf
    
    <div>
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div>
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="mensaje">Mensaje / Consulta:</label>
        <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
    </div>

    <button type="submit">Enviar mensaje</button>
</form>