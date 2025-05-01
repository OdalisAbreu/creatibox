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


    <!-- JS: cargar imagen + cerrar al hacer clic fuera -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalFactura');
            const modalImage = document.getElementById('modalImage');
            const downloadBtn = document.getElementById('downloadBtn');
            const thumbnails = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalFactura"]');

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
        });
    </script>
</x-app-layout>