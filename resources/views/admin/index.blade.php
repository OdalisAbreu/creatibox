<x-app-layout>
    <div class="capture-page">
        <div class="container py-3 py-lg-4">

            <!-- Header -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="brand-dot" aria-hidden="true"></span>
                    <h1 class="h4 mb-0 brand-title">Panel de Capturas</h1>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="button"
                            class="btn btn-brand"
                            id="newRecordBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#newCaptureModal">
                        <i class="fas fa-plus me-1"></i> Nuevo Registro
                    </button>

                    @if ($captures->count() > 0)
                        <a href="{{ url('/admin/export/excel') }}?{{ http_build_query(request()->all()) }}"
                           class="btn btn-brand-slate">
                            <i class="fas fa-file-excel me-1"></i> Exportar Excel
                        </a>

                        <a href="{{ url('/admin/export/pdf') }}?{{ http_build_query(request()->all()) }}"
                           class="btn btn-brand-red-dark"
                           target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Exportar PDF
                        </a>
                    @endif
                </div>
            </div>

            <!-- KPI + Filtros -->
            <div class="row g-3 align-items-stretch mb-3">

                <!-- KPI -->
                <div class="col-12 col-lg-3">
                    <div class="card brand-kpi shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="small opacity-75">Total Registros</div>
                            <div class="fs-3 fw-semibold">{{ $totalCount }}</div>
                            <div class="mt-2 small opacity-75">Última actualización: {{ now()->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="col-12 col-lg-9">
                    <div class="card brand-card shadow-sm h-100">
                        <div class="card-body">
                            <form method="GET">
                                <div class="row g-2">
                                    <div class="col-12 col-md-5">
                                        <label class="form-label small mb-1 text-muted">Código</label>
                                        <input type="text"
                                               name="code"
                                               value="{{ request('code') }}"
                                               class="form-control brand-input"
                                               placeholder="Ej: ABC-123">
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <label class="form-label small mb-1 text-muted">Departamento</label>
                                        <select name="department" class="form-select brand-input">
                                            <option value="">Todos los departamentos</option>
                                            <option value="CONTABILIDAD Y FINANZAS" {{ request('department') == 'CONTABILIDAD Y FINANZAS' ? 'selected' : '' }}>CONTABILIDAD Y FINANZAS</option>
                                            <option value="COBROS Y COMISIONES" {{ request('department') == 'COBROS Y COMISIONES' ? 'selected' : '' }}>COBROS Y COMISIONES</option>
                                            <option value="GESTION DE CUENTAS" {{ request('department') == 'GESTION DE CUENTAS' ? 'selected' : '' }}>GESTION DE CUENTAS</option>
                                            <option value="CONSEJO" {{ request('department') == 'CONSEJO' ? 'selected' : '' }}>CONSEJO</option>
                                            <option value="CUMPLIMIENTO Y CALIDAD" {{ request('department') == 'CUMPLIMIENTO Y CALIDAD' ? 'selected' : '' }}>CUMPLIMIENTO Y CALIDAD</option>
                                            <option value="GESTION DEL TALENTO HUMANO" {{ request('department') == 'GESTION DEL TALENTO HUMANO' ? 'selected' : '' }}>GESTION DEL TALENTO HUMANO</option>
                                            <option value="MERCADEO Y COMUNICACIONES" {{ request('department') == 'MERCADEO Y COMUNICACIONES' ? 'selected' : '' }}>MERCADEO Y COMUNICACIONES</option>
                                            <option value="OPERACIONES" {{ request('department') == 'OPERACIONES' ? 'selected' : '' }}>OPERACIONES</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                                    <button type="submit" class="btn btn-brand">
                                        <i class="fas fa-filter me-1"></i> Filtrar
                                    </button>
                                    <a href="{{ url('/admin') }}" class="btn btn-brand-outline">
                                        <i class="fas fa-broom me-1"></i> Limpiar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Vista móvil: Cards -->
            <div class="d-md-none">
                @foreach ($captures as $capture)
                    <div class="card brand-card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">
                                        <a href="/capture/{{ $capture->Code }}" class="brand-link text-decoration-none">
                                            #{{ ($captures->currentPage() - 1) * $captures->perPage() + $loop->iteration }} - {{ Str::limit($capture->Code, 20) }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-1"><strong>Descripción:</strong> {{ Str::limit($capture->Description, 40) }}</p>
                                    <p class="text-muted small mb-1"><strong>Depto:</strong> {{ Str::limit($capture->department, 25) }}</p>
                                    <p class="text-muted small mb-1"><strong>Sucursal:</strong> {{ $capture->sucursal }}</p>
                                    <p class="text-muted small mb-0"><strong>Colaborador:</strong> {{ Str::limit($capture->collaborator, 30) }}</p>
                                </div>

                                @if($capture->image_path)
                                    <img src="{{ asset('storage/' . $capture->image_path) }}"
                                         alt="Imagen"
                                         class="rounded object-fit-cover flex-shrink-0 cursor-pointer"
                                         style="width:80px;height:80px;cursor:pointer;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#modalFactura"
                                         data-image="{{ asset('storage/' . $capture->image_path) }}"
                                         data-code="{{ $capture->Code }}">
                                @endif
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button"
                                        class="btn btn-brand-slate btn-sm edit-btn flex-fill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $capture->id }}">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>

                                <button type="button"
                                        class="btn btn-brand-red-dark btn-sm delete-btn flex-fill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-name="{{ $capture->Description }}"
                                        data-code="{{ $capture->Code }}"
                                        data-url="{{ route('admin.deleteCapture', $capture->id) }}">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </button>

                                <button type="button"
                                        class="btn btn-brand btn-sm upload-image-btn flex-fill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#uploadImageModal"
                                        data-id="{{ $capture->id }}">
                                    <i class="fas fa-camera me-1"></i> Imagen
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Vista desktop: Tabla -->
            <div class="d-none d-md-block">
                <div class="card brand-card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 brand-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">No.</th>
                                        <th style="width: 160px;">Código</th>
                                        <th>Descripción</th>
                                        <th style="width: 240px;">Departamento</th>
                                        <th style="width: 130px;">Sucursal</th>
                                        <th style="width: 240px;">Colaborador</th>
                                        <th style="width: 120px;">Imagen</th>
                                        <th style="width: 140px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($captures as $capture)
                                        <tr>
                                            <td>
                                                <span class="brand-link text-decoration-none"
                                                      title="ID: {{ $capture->id }}">
                                                    {{ ($captures->currentPage() - 1) * $captures->perPage() + $loop->iteration }}
                                                </span>
                                            </td>

                                            <td class="text-truncate" style="max-width:160px;" title="{{ $capture->Code }}">
                                                {{ $capture->Code }}
                                            </td>

                                            <td class="text-truncate" style="max-width:360px;">
                                                {{ $capture->Description }}
                                            </td>

                                            <td class="text-truncate" style="max-width:240px;">
                                                {{ $capture->department }}
                                            </td>

                                            <td>{{ $capture->sucursal }}</td>

                                            <td class="text-truncate" style="max-width:240px;">
                                                {{ $capture->collaborator }}
                                            </td>

                                            <td>
                                                @if($capture->image_path)
                                                    <img src="{{ asset('storage/' . $capture->image_path) }}"
                                                         alt="Imagen"
                                                         class="rounded object-fit-cover"
                                                         style="width:72px;height:72px;cursor:pointer;"
                                                         data-bs-toggle="modal"
                                                         data-bs-target="#modalFactura"
                                                         data-image="{{ asset('storage/' . $capture->image_path) }}"
                                                         data-code="{{ $capture->Code }}">
                                                @else
                                                    <span class="text-muted small">Sin imagen</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button"
                                                            class="btn btn-brand-slate btn-sm edit-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editModal"
                                                            data-id="{{ $capture->id }}"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-brand-red-dark btn-sm delete-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            data-name="{{ $capture->Description }}"
                                                            data-code="{{ $capture->Code }}"
                                                            data-url="{{ route('admin.deleteCapture', $capture->id) }}"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-brand btn-sm upload-image-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#uploadImageModal"
                                                            data-id="{{ $capture->id }}"
                                                            title="Subir imagen">
                                                        <i class="fas fa-camera"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                <div class="d-flex justify-content-center">
                    {{ $captures->appends(request()->query())->links() }}
                </div>
            </div>

        </div>

        <!-- Modal de imagen con botón de descarga -->
        <div class="modal fade" id="modalFactura" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content brand-modal rounded shadow position-relative">
                    <div class="modal-header brand-modal-header">
                        <h5 class="modal-title" id="modalImageTitle">Imagen - <span id="modalCode"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center p-2 p-md-3">
                        <div class="image-container-responsive">
                            <img id="modalImage" src="" alt="Imagen" class="img-fluid rounded mb-3 mx-auto d-block">
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <a id="downloadBtn" href="#" class="btn btn-brand" download>
                                <i class="fas fa-download me-1"></i> Descargar Imagen
                            </a>
                            <button type="button" class="btn btn-brand-outline" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .image-container-responsive {
                max-height: 70vh;
                overflow: auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #modalImage {
                max-width: 100%;
                max-height: 70vh;
                width: auto;
                height: auto;
                object-fit: contain;
            }

            @media (max-width: 767px) {
                .modal-dialog.modal-lg {
                    max-width: 95vw;
                    margin: 0.5rem;
                }

                .image-container-responsive {
                    max-height: 60vh;
                }

                #modalImage {
                    max-height: 60vh;
                }

                .modal-body {
                    padding: 1rem !important;
                }
            }

            .cursor-pointer {
                cursor: pointer;
                transition: opacity 0.2s;
            }

            .cursor-pointer:hover {
                opacity: 0.8;
            }
        </style>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content brand-modal">
                    <div class="modal-header brand-modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el registro completo?</p>
                        <div class="alert alert-warning mb-0">
                            <strong>Descripción:</strong> <span id="deleteName"></span><br>
                            <strong>Código:</strong> <span id="deleteCode"></span>
                        </div>
                        <p class="text-danger small mt-2 mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Esta acción eliminará el registro y todas sus imágenes asociadas. Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand-outline" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-brand-red-dark">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para subir imagen -->
        <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content brand-modal">
                    <form id="uploadImageForm" method="POST" action="{{ route('admin.uploadImage') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header brand-modal-header">
                            <h5 class="modal-title" id="uploadImageModalLabel">Subir Imagen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="capture_id" id="captureId">
                            <div class="mb-3">
                                <label for="image" class="form-label">Seleccionar Imagen</label>
                                <input type="file" class="form-control brand-input" id="image" name="image" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-brand-outline" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-brand">Subir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para nuevo Registro -->
        <div class="modal fade" id="newCaptureModal" tabindex="-1" aria-labelledby="newCaptureModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content brand-modal">
                    <form id="newCaptureForm" method="POST" action="{{ route('admin.storeCapture') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header brand-modal-header">
                            <h5 class="modal-title" id="newCaptureModalLabel">Nuevo Registro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong><i class="fas fa-exclamation-triangle"></i> Errores encontrados:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="Code" class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control brand-input @error('Code') is-invalid @enderror" id="Code" name="Code" value="{{ old('Code') }}" required autofocus>
                                @error('Code')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">El código debe ser único</small>
                            </div>

                            <div class="mb-3">
                                <label for="Description" class="form-label">Descripción</label>
                                <input type="text" class="form-control brand-input @error('Description') is-invalid @enderror" id="Description" name="Description" value="{{ old('Description') }}" required>
                                @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Departamento</label>
                                <select class="form-select brand-input @error('department') is-invalid @enderror" id="department" name="department" required>
                                    <option value="">Seleccione un departamento</option>
                                    <option value="CONTABILIDAD Y FINANZAS" {{ old('department') == 'CONTABILIDAD Y FINANZAS' ? 'selected' : '' }}>CONTABILIDAD Y FINANZAS</option>
                                    <option value="COBROS Y COMISIONES" {{ old('department') == 'COBROS Y COMISIONES' ? 'selected' : '' }}>COBROS Y COMISIONES</option>
                                    <option value="GESTION DE CUENTAS" {{ old('department') == 'GESTION DE CUENTAS' ? 'selected' : '' }}>GESTION DE CUENTAS</option>
                                    <option value="CONSEJO" {{ old('department') == 'CONSEJO' ? 'selected' : '' }}>CONSEJO</option>
                                    <option value="CUMPLIMIENTO Y CALIDAD" {{ old('department') == 'CUMPLIMIENTO Y CALIDAD' ? 'selected' : '' }}>CUMPLIMIENTO Y CALIDAD</option>
                                    <option value="GESTION DEL TALENTO HUMANO" {{ old('department') == 'GESTION DEL TALENTO HUMANO' ? 'selected' : '' }}>GESTION DEL TALENTO HUMANO</option>
                                    <option value="MERCADEO Y COMUNICACIONES" {{ old('department') == 'MERCADEO Y COMUNICACIONES' ? 'selected' : '' }}>MERCADEO Y COMUNICACIONES</option>
                                    <option value="OPERACIONES" {{ old('department') == 'OPERACIONES' ? 'selected' : '' }}>OPERACIONES</option>
                                </select>
                                @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sucursal" class="form-label">Sucursal</label>
                                <select class="form-select brand-input @error('sucursal') is-invalid @enderror" id="sucursal" name="sucursal" required>
                                    <option value="">Seleccione una sucursal</option>
                                    <option value="PRINCIPAL" {{ old('sucursal') == 'PRINCIPAL' ? 'selected' : '' }}>PRINCIPAL</option>
                                    <option value="ROMANA" {{ old('sucursal') == 'ROMANA' ? 'selected' : '' }}>ROMANA</option>
                                    <option value="PUNTA CANA" {{ old('sucursal') == 'PUNTA CANA' ? 'selected' : '' }}>PUNTA CANA</option>
                                </select>
                                @error('sucursal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="collaborator" class="form-label">Colaborador Asignado</label>
                                <input type="text"
                                       class="form-control brand-input @error('collaborator') is-invalid @enderror"
                                       id="collaborator"
                                       name="collaborator"
                                       list="collaborators-list"
                                       autocomplete="off"
                                       value="{{ old('collaborator') }}"
                                       required>
                                <datalist id="collaborators-list">
                                    <option value="ANTONIO CACERES TRONCOSO">
                                    <option value="ALBERTO PERALTA MARTINEZ">
                                    <option value="ANTONIO CACERES RICART">
                                    <option value="CARLOS FLORES ROJAS">
                                    <option value="ANTONIA CACERES RICART">
                                    <option value="ISABEL CACERES RICART">
                                    <option value="ERNESTINA MAGNOLIA LORA ORTIZ">
                                    <option value="NIKAURYS ALENNY PENA PENA">
                                    <option value="ROSA NELLY ABAD MUESES">
                                    <option value="LUIS MANUEL SOUSA TEJADA">
                                    <option value="JOSE ALBERTO LUNA SILVERIO">
                                    <option value="GEORGA CAROLINA ROSARIO GUILLEN">
                                    <option value="IVAN JOSE PIMENTEL CASTILLO">
                                    <option value="LEYDI RAMIREZ URENA">
                                    <option value="ARIELA PERDOMO RAMIREZ">
                                    <option value="ANA MICHELY DE LOS SANTOS MELENDEZ">
                                    <option value="IVELICE MUNOZ PENA">
                                    <option value="LILIANA DIAZ RUBIO">
                                    <option value="YASMIN HENRIQUEZ TAVERAS">
                                    <option value="FRANCISCO JOSE DE LEON DAVILA">
                                    <option value="ANA ROSA VELASQUEZ LOPEZ">
                                    <option value="JAZMIN MEIS CALDERON">
                                    <option value="MIRIAM ELLIS">
                                    <option value="ARIS LEIDIS FELIX FULGENCIO">
                                    <option value="CATHERINE GABRIELA NOLASCO GOMEZ">
                                    <option value="YULEISI MARIA VENTURA">
                                    <option value="MARIA VELASQUEZ ACEVEDO">
                                    <option value="VICTOR MANUEL BODRE PEREZ">
                                    <option value="MARIELLY RACHELLE VILLAR DE DIOS">
                                    <option value="JULIO ELIAS VIVENES FIGUEROA">
                                    <option value="BETSY PRISCILLA IZQUIERDO MONTES DE OCA">
                                    <option value="JHON CARLOS PIMENTEL SANCHEZ">
                                    <option value="KIMBERLY SIERRA MEJIA">
                                    <option value="VARINIA ALEJANDRA LARA TRINIDAD">
                                    <option value="PALOMA CRISTINA MILIAN DE GONZALEZ">
                                    <option value="ANDERSON PERALTA PIMENTEL">
                                    <option value="YANELA STEFFANY ALVARADO LINARES">
                                    <option value="FAUSTINA HERNANDEZ">
                                    <option value="ESTEPHANIE ALTAGRACIA NUNEZ LARA">
                                    <option value="SAYRA GISSELLE RAMON LOPEZ">
                                    <option value="CRISTINA AMELIA CACERES JACOBO">
                                    <option value="YAFREYSI AQUINO MONTERO">
                                    <option value="CLAUDIA DAYANARA BERAS ORTIZ">
                                    <option value="MAYELIN ZARZUELA FLORIAN">
                                    <option value="WALKIRIS NAYDELIS RODRIGUEZ SANCHEZ">
                                    <option value="ANGEL LUIS BAEZ ORTIZ">
                                    <option value="CRICEILIN YARLINA UREÑA MEJIA">
                                    <option value="JOSE OCTAVIO MATEO CABRAL">
                                    <option value="JOAN MANUEL BENZAN HERRERA">
                                    <option value="BELGICA CORPORAN LORA">
                                    <option value="MERCEDES PANIAGUA DE LOS SANTOS">
                                    <option value="MISMARIN SCALHIN FAMILIA FRANCO">
                                    <option value="MADELIN MICHELLE NEPOMUCENO HERRERA">
                                    <option value="JANA LORAINE ALMONTE GUZMAN">
                                    <option value="AMBIORIX DE JESUS DE LA CRUZ REYES">
                                    <option value="DENIS RIVERA HERNADEZ">
                                    <option value="MARLENNE RUIZ BISONO">
                                    <option value="WILMY ANTONIO DE LA ROSA SILVA">
                                    <option value="HEIDI SUSANA AGRAMONTE JIMENEZ">
                                    <option value="JOSE RAMON CARABALLO CAMILO">
                                    <option value="ODALIS DARIEL ABREU MEJIA">
                                    <option value="ROSANNA FLORENTINO">
                                    <option value="ANALIA AIMEE ALVAREZ DINA">
                                    <option value="MELISSA HIDALGO ARIAS">
                                    <option value="PAOLA YAZMIN MORDAN LARA">
                                    <option value="ESTHER GARCIA ESPINAL">
                                    <option value="PEDRO ANTONIO RAMIREZ SUAREZ">
                                    <option value="EMILY LAURA RAMOS GONZALEZ">
                                    <option value="JENNIFER NATHALI GENAO REYES">
                                    <option value="EDDY DE JESUS SANTOS SANTANA">
                                    <option value="KATHERINE LISSELOTTE REYES TAVAREZ">
                                    <option value="YISEL YUSBELKIS FONTANILLAS PEREZ">
                                    <option value="JOAN GARCIA">
                                </datalist>
                                @error('collaborator') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="invoice_image" class="form-label">Imagen</label>
                                <input type="file" class="form-control brand-input @error('invoice_image') is-invalid @enderror" id="invoice_image" name="invoice_image" accept="image/*" required>
                                @error('invoice_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-brand-outline" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-brand">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para editar registro -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content brand-modal">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header brand-modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar Registro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div id="editErrors" class="alert brand-alert d-none">
                                <ul class="mb-0" id="editErrorsList"></ul>
                            </div>

                            <div class="mb-3">
                                <label for="edit_Code" class="form-label">Código</label>
                                <input type="text" class="form-control brand-input" id="edit_Code" name="Code" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_Description" class="form-label">Descripción</label>
                                <input type="text" class="form-control brand-input" id="edit_Description" name="Description" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_department" class="form-label">Departamento</label>
                                <select class="form-select brand-input" id="edit_department" name="department" required>
                                    <option value="">Seleccione un departamento</option>
                                    <option value="CONTABILIDAD Y FINANZAS">CONTABILIDAD Y FINANZAS</option>
                                    <option value="COBROS Y COMISIONES">COBROS Y COMISIONES</option>
                                    <option value="GESTION DE CUENTAS">GESTION DE CUENTAS</option>
                                    <option value="CONSEJO">CONSEJO</option>
                                    <option value="CUMPLIMIENTO Y CALIDAD">CUMPLIMIENTO Y CALIDAD</option>
                                    <option value="GESTION DEL TALENTO HUMANO">GESTION DEL TALENTO HUMANO</option>
                                    <option value="MERCADEO Y COMUNICACIONES">MERCADEO Y COMUNICACIONES</option>
                                    <option value="OPERACIONES">OPERACIONES</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_sucursal" class="form-label">Sucursal</label>
                                <select class="form-select brand-input" id="edit_sucursal" name="sucursal" required>
                                    <option value="">Seleccione una sucursal</option>
                                    <option value="PRINCIPAL">PRINCIPAL</option>
                                    <option value="ROMANA">ROMANA</option>
                                    <option value="PUNTA CANA">PUNTA CANA</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_collaborator" class="form-label">Colaborador Asignado</label>
                                <input type="text"
                                       class="form-control brand-input"
                                       id="edit_collaborator"
                                       name="collaborator"
                                       list="collaborators-list"
                                       autocomplete="off"
                                       required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-brand-outline" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-brand" id="editSubmitBtn">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Estilos: rebranding con paleta -->
        <style>
            .capture-page{
                --brand-red: #E31937;
                --brand-red-dark: #C41230;
                --brand-slate: #425968;
                --brand-gray: #949CA1;

                --brand-bg: #F7F8FA;
                --brand-text: #16202A;
                --brand-border: rgba(66, 89, 104, .18);

                background: var(--brand-bg);
                min-height: 100%;
            }

            .capture-page .brand-title{
                color: var(--brand-text);
                letter-spacing: .2px;
            }

            .capture-page .brand-dot{
                width: 10px;
                height: 10px;
                border-radius: 999px;
                background: var(--brand-red);
                box-shadow: 0 0 0 6px rgba(227, 25, 55, .12);
            }

            .capture-page .brand-card{
                border: 1px solid var(--brand-border);
                border-radius: 14px;
            }

            .capture-page .brand-kpi{
                color: #fff;
                border: 0;
                border-radius: 14px;
                background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            }

            .capture-page .brand-input{
                border-radius: 12px;
                border: 1px solid var(--brand-border);
            }
            .capture-page .brand-input:focus{
                border-color: rgba(227, 25, 55, .55);
                box-shadow: 0 0 0 .2rem rgba(227, 25, 55, .15);
            }

            .capture-page .btn{
                border-radius: 12px;
                font-weight: 600;
                letter-spacing: .2px;
            }

            .capture-page .btn-brand{
                background: var(--brand-red);
                border: 1px solid var(--brand-red);
                color: #fff;
            }
            .capture-page .btn-brand:hover{
                background: var(--brand-red-dark);
                border-color: var(--brand-red-dark);
                color: #fff;
            }

            .capture-page .btn-brand-red-dark{
                background: var(--brand-red-dark);
                border: 1px solid var(--brand-red-dark);
                color: #fff;
            }
            .capture-page .btn-brand-red-dark:hover{
                filter: brightness(0.95);
                color: #fff;
            }

            .capture-page .btn-brand-slate{
                background: var(--brand-slate);
                border: 1px solid var(--brand-slate);
                color: #fff;
            }
            .capture-page .btn-brand-slate:hover{
                filter: brightness(0.95);
                color: #fff;
            }

            .capture-page .btn-brand-outline{
                background: #fff;
                border: 1px solid var(--brand-border);
                color: var(--brand-slate);
            }
            .capture-page .btn-brand-outline:hover{
                border-color: rgba(66, 89, 104, .35);
                background: rgba(148, 156, 161, .10);
                color: var(--brand-slate);
            }

            .capture-page .brand-link{
                color: var(--brand-red-dark);
                font-weight: 700;
            }
            .capture-page .brand-link:hover{
                color: var(--brand-red);
            }

            .capture-page .brand-table thead{
                background: var(--brand-slate);
                color: #fff;
            }
            .capture-page .brand-table thead th{
                font-weight: 700;
                border-bottom: 0;
                white-space: nowrap;
            }
            .capture-page .brand-table tbody tr{
                background: #fff;
            }
            .capture-page .brand-table tbody tr:hover{
                background: rgba(148, 156, 161, .10);
            }
            .capture-page .brand-table td{
                border-color: rgba(66, 89, 104, .12);
            }

            .capture-page .brand-modal{
                border-radius: 14px;
                border: 1px solid var(--brand-border);
            }
            .capture-page .brand-modal-header{
                background: rgba(66, 89, 104, .06);
                border-bottom: 1px solid var(--brand-border);
            }

            .capture-page .brand-alert{
                border: 1px solid rgba(227, 25, 55, .25);
                background: rgba(227, 25, 55, .08);
                color: var(--brand-red-dark);
                border-radius: 12px;
            }

            .capture-page .object-fit-cover { object-fit: cover; }

            .capture-page .pagination { flex-wrap: wrap; justify-content: center; gap: .25rem; }
            .capture-page .page-link{
                border-radius: 10px;
                border: 1px solid var(--brand-border);
                color: var(--brand-slate);
            }
            .capture-page .page-item.active .page-link{
                background: var(--brand-red);
                border-color: var(--brand-red);
                color: #fff;
            }
        </style>

        <!-- JS (sin cambios funcionales) -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Tooltip (una sola vez)
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

                // Modal imagen
                const modal = document.getElementById('modalFactura');
                const modalImage = document.getElementById('modalImage');
                const downloadBtn = document.getElementById('downloadBtn');
                const modalCode = document.getElementById('modalCode');
                const thumbnails = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalFactura"]');

                thumbnails.forEach(img => {
                    img.addEventListener('click', () => {
                        const imageUrl = img.getAttribute('data-image');
                        const code = img.getAttribute('data-code');
                        
                        modalImage.src = imageUrl;
                        downloadBtn.href = imageUrl;
                        
                        // Obtener la extensión del archivo para el nombre de descarga
                        const urlParts = imageUrl.split('.');
                        const extension = urlParts[urlParts.length - 1];
                        downloadBtn.download = code ? `${code}.${extension}` : 'imagen.' + extension;
                        
                        // Actualizar el código en el título del modal
                        if (modalCode) {
                            modalCode.textContent = code || 'N/A';
                        }
                    });
                });

                modal?.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        bsModal?.hide();
                    }
                });

                // Eliminar
                const deleteName = document.getElementById('deleteName');
                const deleteCode = document.getElementById('deleteCode');
                const deleteForm = document.getElementById('deleteForm');

                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const name = this.getAttribute('data-name');
                        const code = this.getAttribute('data-code');
                        const url = this.getAttribute('data-url');
                        deleteName.textContent = name || 'N/A';
                        deleteCode.textContent = code || 'N/A';
                        deleteForm.setAttribute('action', url);
                    });
                });

                // Subir imagen
                const captureIdInput = document.getElementById('captureId');
                document.querySelectorAll('.upload-image-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        captureIdInput.value = this.getAttribute('data-id');
                    });
                });

                // Editar: cargar datos y setear el action del form
                const editForm = document.getElementById('editForm');
                const editSubmitBtn = document.getElementById('editSubmitBtn');
                const editErrors = document.getElementById('editErrors');
                const editErrorsList = document.getElementById('editErrorsList');

                const editCode = document.getElementById('edit_Code');
                const editDescription = document.getElementById('edit_Description');
                const editDepartment = document.getElementById('edit_department');
                const editSucursal = document.getElementById('edit_sucursal');
                const editCollaborator = document.getElementById('edit_collaborator');

                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', async function () {
                        const captureId = this.getAttribute('data-id');

                        editErrors.classList.add('d-none');
                        editErrorsList.innerHTML = '';

                        editSubmitBtn.disabled = true;
                        editSubmitBtn.textContent = 'Cargando...';
                        showLoading();

                        try {
                            const response = await fetch(`/admin/edit/${captureId}`);
                            if (!response.ok) throw new Error('Error al cargar los datos');

                            const data = await response.json();

                            editCode.value = data.Code || '';
                            editDescription.value = data.Description || '';
                            editDepartment.value = data.department || '';
                            editSucursal.value = data.sucursal || '';
                            editCollaborator.value = data.collaborator || '';

                            // Ajusta si tu endpoint update es distinto
                            editForm.action = `/admin/update/${captureId}`;
                        } catch (err) {
                            editErrors.classList.remove('d-none');
                            const li = document.createElement('li');
                            li.textContent = 'No se pudieron cargar los datos del registro. Intenta de nuevo.';
                            editErrorsList.appendChild(li);
                        } finally {
                            editSubmitBtn.disabled = false;
                            editSubmitBtn.textContent = 'Actualizar';
                            hideLoading();
                        }
                    });
                });

                // Submit edición
                editForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    editErrors.classList.add('d-none');
                    editErrorsList.innerHTML = '';

                    editSubmitBtn.disabled = true;
                    const originalText = editSubmitBtn.textContent;
                    editSubmitBtn.textContent = 'Actualizando...';
                    showLoading();

                    try {
                        const formData = new FormData(editForm);

                        const response = await fetch(editForm.action, {
                            method: 'POST', // con _method=PUT
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert(data.message || 'Registro actualizado');
                            location.reload();
                            return;
                        }

                        editErrors.classList.remove('d-none');
                        const li = document.createElement('li');
                        li.textContent = data.message || 'Error al actualizar el registro';
                        editErrorsList.appendChild(li);

                    } catch (err) {
                        editErrors.classList.remove('d-none');
                        const li = document.createElement('li');
                        li.textContent = 'Error inesperado al actualizar. Intenta de nuevo.';
                        editErrorsList.appendChild(li);
                    } finally {
                        editSubmitBtn.disabled = false;
                        editSubmitBtn.textContent = originalText;
                        hideLoading();
                    }
                });

            });
        </script>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Procesando...</p>
            </div>
        </div>

        <style>
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .loading-spinner {
                text-align: center;
                background: white;
                padding: 2rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .spinner-border {
                border-width: 0.25em;
            }
        </style>

        <script>
            // Función para mostrar loading
            function showLoading() {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }

            // Función para ocultar loading
            function hideLoading() {
                document.getElementById('loadingOverlay').style.display = 'none';
            }

            // Función para limpiar el formulario de nuevo registro
            function clearNewCaptureForm() {
                const form = document.getElementById('newCaptureForm');
                if (form) {
                    form.reset();
                    // Limpiar clases de error
                    form.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    // Limpiar mensajes de error
                    form.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.remove();
                    });
                    // Limpiar alertas
                    const alert = form.querySelector('.alert-danger');
                    if (alert) {
                        alert.remove();
                    }
                    // Resetear selects a su primera opción
                    form.querySelectorAll('select').forEach(select => {
                        select.selectedIndex = 0;
                    });
                }
            }

            // Manejar el modal de nuevo registro
            const newCaptureModal = document.getElementById('newCaptureModal');
            
            if (newCaptureModal) {
                // NO limpiar cuando se abre el modal si hay errores (para mantener los valores)
                newCaptureModal.addEventListener('show.bs.modal', function() {
                    // Solo limpiar si no hay errores de validación
                    const hasFormErrors = document.querySelector('#newCaptureForm .alert-danger') !== null ||
                                         document.querySelectorAll('#newCaptureForm .is-invalid').length > 0;
                    if (!hasFormErrors) {
                        clearNewCaptureForm();
                    }
                });

                // NO limpiar cuando se cierra el modal si hay errores (para mantener los valores)
                newCaptureModal.addEventListener('hidden.bs.modal', function() {
                    // Solo limpiar si no hay errores de validación
                    const hasFormErrors = document.querySelector('#newCaptureForm .alert-danger') !== null ||
                                         document.querySelectorAll('#newCaptureForm .is-invalid').length > 0;
                    if (!hasFormErrors) {
                        clearNewCaptureForm();
                    }
                });
            }
        </script>

        @if($errors->any())
        <script>
            // Abrir el modal automáticamente si hay errores
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('newCaptureModal'));
                modal.show();
            });
        </script>
        @endif

        <script>
        </script>

        @if(session('success'))
        <script>
            // Limpiar formulario si hay mensaje de éxito al cargar la página
            document.addEventListener('DOMContentLoaded', function() {
                clearNewCaptureForm();
                // Cerrar el modal si está abierto
                const modal = bootstrap.Modal.getInstance(document.getElementById('newCaptureModal'));
                if (modal) {
                    modal.hide();
                }
            });
        </script>
        @endif

        <script>

            // Loading para formulario de nuevo registro
            document.getElementById('newCaptureForm')?.addEventListener('submit', function(e) {
                showLoading();
            });

            // Loading para formulario de subir imagen
            document.getElementById('uploadImageForm')?.addEventListener('submit', function(e) {
                showLoading();
            });

            // Loading para formulario de eliminar
            document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
                showLoading();
            });

            // Loading para formulario de edición
            document.getElementById('editForm')?.addEventListener('submit', function(e) {
                showLoading();
            });

            // Ocultar loading cuando la página se carga completamente
            window.addEventListener('load', function() {
                hideLoading();
            });
        </script>

        @if($errors->any())
        <script>
            // Ocultar loading si hay errores de validación (cuando se recarga la página con errores)
            hideLoading();
        </script>
        @endif
    </div>
</x-app-layout>
