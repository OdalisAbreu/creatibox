<x-app-layout>
    <div class="container mt-5">
        <h1 class="h2 mb-4">📄 Panel de Capturas</h1>

        <!-- Filtros generales -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Buscar por nombre">
            </div>
            <div class="col-md-3">
                <input type="text" name="cell_phone" value="{{ request('cell_phone') }}" class="form-control" placeholder="Buscar por celular">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ url('/admin/export/excel') }}" class="btn btn-success me-2">⬇️ Exportar Excel</a>
                <a href="{{ url('/admin/export/pdf') }}" class="btn btn-danger">⬇️ Exportar PDF</a>
            </div>
        </form>

        <!-- Tabla de capturas -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Género</th>
                        <th>Edad</th>
                        <th>Card ID</th>
                        <th>Celular</th>
                        <th>Factura</th>
                        <th>Estado</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($captures as $capture)
                    <tr>
                        <td>{{ $capture->name }}</td>
                        <td>{{ $capture->email }}</td>
                        <td>{{ ucfirst($capture->gender) }}</td>
                        <td>{{ $capture->age }}</td>
                        <td>{{ $capture->card_id }}</td>
                        <td>{{ $capture->cell_phone }}</td>
                        <td>
                            @if ($capture->image_path)
                            <img src="{{ Storage::url($capture->image_path) }}"
                                alt="Factura"
                                class="img-thumbnail"
                                style="max-width: 70px; cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#modalFactura"
                                data-image="{{ Storage::url($capture->image_path) }}">
                            @else
                            <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>
                            @if ($capture->completed)
                            <span class="badge bg-success">Completado</span>
                            @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <!-- Botón de eliminar -->
                            @if ($capture->image_id)
                            <button type="button"
                                class="btn btn-danger btn-lg d-flex justify-content-center align-items-center delete-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-name="{{ $capture->name }}"
                                data-url="{{ route('admin.deleteCapture', $capture->image_id) }}"
                                style="width: 50px; height: 50px;"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Solo eliminará la factura">
                                <i class="fas fa-trash-alt" style="font-size: 1.5rem;"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $captures->links() }}
        </div>
    </div>


    <!-- Modal de imagen con botón de descarga -->
    <div class="modal fade" id="modalFactura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content bg-white rounded shadow p-3 position-relative">

                <!-- Botón cerrar -->
                <!-- <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Cerrar"></button> -->

                <!-- Imagen -->
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Factura Grande" class="img-fluid rounded mb-3 mx-auto d-block" style="max-height: 75vh;">

                    <br>
                    <a id="downloadBtn" href="#" class="btn btn-outline-primary" download target="_blank">
                        <i class="fas fa-download me-1"></i> Descargar Factura
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------------------------------------------------------------->

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar la factura de <strong id="deleteName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------------------------------------------->

    <!-- JS: cargar imagen + cerrar al hacer clic fuera -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalFactura');
            const modalImage = document.getElementById('modalImage');
            const downloadBtn = document.getElementById('downloadBtn');
            const thumbnails = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalFactura"]');

            const deleteModal = document.getElementById('deleteModal');
            const deleteName = document.getElementById('deleteName');
            const deleteForm = document.getElementById('deleteForm');

            thumbnails.forEach(img => {
                img.addEventListener('click', () => {
                    const imageUrl = img.getAttribute('data-image');
                    modalImage.src = imageUrl;
                    downloadBtn.href = imageUrl;
                });
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    bsModal.hide();
                }
            });

            // Escucha los clics en los botones de eliminar
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const url = this.getAttribute('data-url');

                    // Actualiza el contenido del modal
                    deleteName.textContent = name;
                    deleteForm.setAttribute('action', url);
                });

                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        });
    </script>
</x-app-layout>