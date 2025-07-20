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
            <div class="me-2">
                <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Nombre">
            </div>
            <div class="me-2">
                <input type="text" name="cell_phone" value="{{ request('cell_phone') }}" class="form-control" placeholder="Celular">
            </div>
            <div class="me-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Fecha inicio">
            </div>
            <div class="me-2">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Fecha fin">
            </div>
            <div class="me-3" style="min-width: 150px;">
                <select name="status" class="form-control">
                    <option value="">Estados</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                </select>
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
                        <th>No. Factura</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Card ID</th>
                        <th>Num. Contacto</th>
                        <th>Estado</th>
                        <th>Factura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($captures as $capture)
                    <tr>
                        <td><a href="/capture/{{ $capture->cell_phone }}">{{ $capture->invoice_number }}</a></td>
                        <td title="{{ $capture->name }}" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($capture->name, 15) }}</td>
                        <td title="{{ $capture->last_name }}" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($capture->last_name, 15) }}</td>
                        <td>{{ $capture->card_id }}</td>
                        <td>{{ $capture->contact_number ?? $capture->cell_phone }}</td>
                        <td>
                            @if ($capture->completed)
                            <span class="badge bg-success">Completado</span>
                            @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
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
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <!-- Botón de editar -->
                                <button type="button"
                                    class="btn btn-warning d-flex justify-content-center align-items-center edit-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="{{ $capture->id }}"
                                    style="width: 50px; height: 50px;"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Editar registro">
                                    <i class="fas fa-edit" style="font-size: 1.5rem; color: white;"></i>
                                </button>

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
                            <label for="invoice_number" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required>
                            @error('invoice_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="card_id" class="form-label">Cédula</label>
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
                            <label for="contact_number" class="form-label">Número de Contacto</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Ciudad</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="storage" class="form-label">Almacén</label>
                            <input type="text" class="form-control @error('storage') is-invalid @enderror" id="storage" name="storage" value="{{ old('storage') }}" required>
                            @error('storage')
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

    <!-- Modal para editar participante -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Participante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editErrors" class="alert alert-danger" style="display: none;">
                            <ul class="mb-0" id="editErrorsList"></ul>
                        </div>
                        <div class="mb-3">
                            <label for="edit_invoice_number" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control" id="edit_invoice_number" name="invoice_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_card_id" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="edit_card_id" name="card_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="cell_phone" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="cell_phone" name="cell_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_contact_number" class="form-label">Número de Contacto</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="contact_number">
                        </div>
                        <div class="mb-3">
                            <label for="edit_city" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_storage" class="form-label">Almacén</label>
                            <input type="text" class="form-control" id="edit_storage" name="storage" required>
                        </div>
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

            // Funcionalidad para editar
            const editButtons = document.querySelectorAll('.edit-btn');
            const editForm = document.getElementById('editForm');
            const editErrors = document.getElementById('editErrors');
            const editErrorsList = document.getElementById('editErrorsList');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const captureId = this.getAttribute('data-id');
                    
                    // Mostrar indicador de carga
                    const modal = document.getElementById('editModal');
                    const modalBody = modal.querySelector('.modal-body');
                    modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
                    
                    // Cargar datos del registro
                    fetch(`/admin/edit/${captureId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al cargar los datos');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Restaurar el contenido del modal
                            modalBody.innerHTML = `
                                <div id="editErrors" class="alert alert-danger" style="display: none;">
                                    <ul class="mb-0" id="editErrorsList"></ul>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_invoice_number" class="form-label">Número de Factura</label>
                                    <input type="text" class="form-control" id="edit_invoice_number" name="invoice_number" value="${data.invoice_number}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" value="${data.name}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_last_name" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="edit_last_name" name="last_name" value="${data.last_name}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_card_id" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="edit_card_id" name="card_id" value="${data.card_id}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_cell_phone" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="edit_cell_phone" name="cell_phone" value="${data.cell_phone}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_contact_number" class="form-label">Número de Contacto</label>
                                    <input type="text" class="form-control" id="edit_contact_number" name="contact_number" value="${data.contact_number || ''}">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_city" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="edit_city" name="city" value="${data.city}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_storage" class="form-label">Almacén</label>
                                    <input type="text" class="form-control" id="edit_storage" name="storage" value="${data.storage}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="submitEditForm('${captureId}')">Actualizar</button>
                                </div>
                            `;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los datos del registro. Por favor, inténtalo de nuevo.</div>';
                        });
                });
            });

            // Manejar envío del formulario de edición usando delegación de eventos
            document.addEventListener('submit', function(e) {
                console.log('Evento submit capturado:', e.target.id);
                
                if (e.target.id === 'editForm') {
                    e.preventDefault();
                    console.log('Formulario de edición detectado');
                    
                    const submitBtn = e.target.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Actualizando...';
                    submitBtn.disabled = true;
                    
                    const formData = new FormData(e.target);
                    
                    // Debug: mostrar los datos que se van a enviar
                    console.log('Datos a enviar:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ': ' + value);
                    }
                    
                    console.log('URL de destino:', e.target.action);
                    
                    fetch(e.target.action, {
                        method: 'PUT',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        console.log('Respuesta recibida:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos de respuesta:', data);
                        if (data.success) {
                            // Mostrar mensaje de éxito
                            alert(data.message);
                            // Recargar la página para mostrar los cambios
                            location.reload();
                        } else {
                            // Mostrar errores
                            const editErrors = document.getElementById('editErrors');
                            const editErrorsList = document.getElementById('editErrorsList');
                            if (editErrors && editErrorsList) {
                                editErrors.style.display = 'block';
                                editErrorsList.innerHTML = '';
                                if (data.message) {
                                    const li = document.createElement('li');
                                    li.textContent = data.message;
                                    editErrorsList.appendChild(li);
                                }
                            } else {
                                alert(data.message || 'Error al actualizar el registro');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        alert('Error al actualizar el registro. Por favor, inténtalo de nuevo.');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                }
            });
            
            // También agregar un listener específico para el modal de edición
            document.addEventListener('click', function(e) {
                if (e.target && e.target.closest('#editModal')) {
                    const form = e.target.closest('#editModal').querySelector('form');
                    if (form && !form.hasAttribute('data-submit-listener')) {
                        form.setAttribute('data-submit-listener', 'true');
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            console.log('Submit desde modal detectado');
                            
                            const formData = new FormData(this);
                            console.log('Datos del modal:');
                            for (let [key, value] of formData.entries()) {
                                console.log(key + ': ' + value);
                            }
                            
                            fetch(this.action, {
                                method: 'PUT',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    location.reload();
                                } else {
                                    alert(data.message || 'Error al actualizar');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al actualizar el registro');
                            });
                        });
                    }
                }
            });
        });
        
        // Función global para enviar el formulario de edición
        function submitEditForm(captureId) {
            console.log('Función submitEditForm llamada con ID:', captureId);
            
            const modal = document.getElementById('editModal');
            const formData = new FormData();
            
            // Recopilar datos del modal
            const inputs = modal.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.name && input.value) {
                    formData.append(input.name, input.value);
                    console.log('Agregando campo:', input.name, '=', input.value);
                }
            });
            
            // Agregar CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            
            console.log('Enviando datos a:', `/admin/update/${captureId}`);
            
            fetch(`/admin/update/${captureId}`, {
                method: 'POST', // Usar POST para que funcione con FormData
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Respuesta recibida:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos de respuesta:', data);
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al actualizar el registro');
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                alert('Error al actualizar el registro. Por favor, inténtalo de nuevo.');
            });
        }
    </script>
</x-app-layout>