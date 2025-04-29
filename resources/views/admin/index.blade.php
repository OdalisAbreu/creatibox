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
                        <th>Estado</th>
                    </tr>
                    <tr>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Filtrar nombre" disabled></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Filtrar email" disabled></th>
                        <th><select class="form-select form-select-sm" disabled>
                                <option selected>Todos</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select></th>
                        <th><input type="number" class="form-control form-control-sm" placeholder="Edad" disabled></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Card ID" disabled></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Celular" disabled></th>
                        <th><select class="form-select form-select-sm" disabled>
                                <option selected>Todos</option>
                                <option>Completado</option>
                                <option>Pendiente</option>
                            </select></th>
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
</x-app-layout>