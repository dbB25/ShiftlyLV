{{--
    Ventana para confirmar una eliminación. Cada botón que la abre indica:
      data-accion  = URL del recurso que se elimina
      data-mensaje = texto que se muestra al usuario
--}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="" class="modal-content" id="formEliminar">
            @csrf
            @method('DELETE')

            <div class="modal-header">
                <h2 class="modal-title h5" id="modalEliminarTitulo">Confirmar eliminación</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0" id="modalEliminarMensaje">¿Seguro que quieres eliminar este registro?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (evento) {
            const boton = evento.relatedTarget;

            document.getElementById('formEliminar').action = boton.dataset.accion;
            document.getElementById('modalEliminarMensaje').textContent = boton.dataset.mensaje;
        });
    </script>
@endpush
