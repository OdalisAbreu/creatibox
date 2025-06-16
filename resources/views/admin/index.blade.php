<x-app-layout>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">📄 Panel de Capturas</h1>
            <div>
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#newParticipantModal">
                    <i class="fas fa-plus"></i> Nuevo Participante
                </button>
                @if ($captures->count() > 0)
                <a href="{{ url('/admin/export/excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="{{ url('/admin/export/pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                @endif
            </div>
        </div>
        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pendientes</h5>
                        <p class="card-text fs-4">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Completados</h5>
                        <p class="card-text fs-4">{{ $completedCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Registros</h5>
                        <p class="card-text fs-4">{{ $totalCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros generales -->
        <form method="GET" class="d-flex align-items-center mb-4">
            <div class="me-3">
                <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Buscar por nombre">
            </div>
            <div class="me-3">
                <input type="text" name="cell_phone" value="{{ request('cell_phone') }}" class="form-control" placeholder="Buscar por celular">
            </div>
            <div class="me-3">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Fecha inicio">
            </div>
            <div class="me-3">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Fecha fin">
            </div>
            <div class="me-2">
                <button type="submit" class="btn btn-primary me-2">🔍 Filtrar</button>
            </div>
            <div class="me-2">
                <a href="{{ url('/admin') }}" class="btn btn-secondary">🧹 Limpiar</a>
            </div>
            <div>
                <button type="button" class="btn btn-info" onclick="location.reload();">🔄 Actualizar</button>
            </div>
        </form>

        <!-- Tabla de capturas -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th style="max-width: 45px;">Email</th>
                        <th>Género</th>
                        <th>Edad</th>
                        <th>Card ID</th>
                        <th>Celular</th>
                        <th>Factura</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($captures as $capture)
                    <tr>
                    <td><a href="/capture/{{ $capture->cell_phone }}">  {{ $capture->name }} </a></td>
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
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <!-- Botón de eliminar -->
                                @if ($capture->image_id)
                                <button type="button"
                                    class="btn btn-danger d-flex justify-content-center align-items-center delete-btn"
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

                                <!-- Botón de subir imagen -->
                                <button type="button"
                                    class="btn btn-primary d-flex justify-content-center align-items-center upload-image-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#uploadImageModal"
                                    data-id="{{ $capture->id }}"
                                    style="width: 50px; height: 50px;"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Subir imagen">
                                    <i class="fas fa-camera" style="font-size: 1.5rem;"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $captures->appends(request()->query())->links() }}
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


    <!-- Modal para subir imagen -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="uploadImageForm" method="POST" action="{{ route('admin.uploadImage') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadImageModalLabel">Subir Imagen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="capture_id" id="captureId">
                        <div class="mb-3">
                            <label for="image" class="form-label">Seleccionar Imagen</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--------------------------------------------------------------------------------------->

    <!-- Modal para nuevo participante -->
    <div class="modal fade" id="newParticipantModal" tabindex="-1" aria-labelledby="newParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="newParticipantForm" method="POST" action="{{ route('admin.storeCapture') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newParticipantModalLabel">Nuevo Participante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Género</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Seleccione...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Edad</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" required min="0">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="card_id" class="form-label">Card ID</label>
                            <input type="text" class="form-control @error('card_id') is-invalid @enderror" id="card_id" name="card_id" value="{{ old('card_id') }}" required>
                            @error('card_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cell_phone" class="form-label">Celular</label>
                            <input type="text" class="form-control @error('cell_phone') is-invalid @enderror" id="cell_phone" name="cell_phone" value="{{ old('cell_phone') }}" required>
                            @error('cell_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="invoice_image" class="form-label">Factura</label>
                            <input type="file" class="form-control @error('invoice_image') is-invalid @enderror" id="invoice_image" name="invoice_image" accept="image/*" required>
                            @error('invoice_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
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
            const uploadButtons = document.querySelectorAll('.upload-image-btn');
            const captureIdInput = document.getElementById('captureId');

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


            uploadButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const captureId = this.getAttribute('data-id');
                    captureIdInput.value = captureId;
                });
            });
        });
    </script>
</x-app-layout>