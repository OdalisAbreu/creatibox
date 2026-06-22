<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administración de Usuarios') }}
        </h2>
    </x-slot>

    <div class="container py-4" style="max-width: 1024px;">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Usuarios</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus me-1"></i> Nuevo Usuario
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Usuario</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit-user"
                                    data-id="{{ $user->id }}"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if(auth()->id() !== $user->id)
                                <button class="btn btn-danger btn-sm btn-delete-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal: Crear Usuario --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="create_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="create_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="create_password" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="create_password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required minlength="8">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="create_is_admin" name="is_admin" value="1">
                            <label class="form-check-label" for="create_is_admin">Administrador</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Editar Usuario --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editUserErrors" class="alert alert-danger d-none"></div>
                        <input type="hidden" id="edit_user_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Nueva Contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                            <input type="password" class="form-control" id="edit_password" name="password" minlength="8">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_admin" name="is_admin" value="1">
                            <label class="form-check-label" for="edit_is_admin">Administrador</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Eliminar Usuario --}}
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Eliminar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de eliminar a <strong id="delete_user_name"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Editar usuario
        document.querySelectorAll('.btn-edit-user').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                var errorsDiv = document.getElementById('editUserErrors');
                errorsDiv.classList.add('d-none');

                fetch('/admin/users/' + id)
                    .then(function(r) { return r.json(); })
                    .then(function(user) {
                        document.getElementById('edit_user_id').value = user.id;
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_password').value = '';
                        document.getElementById('edit_is_admin').checked = user.is_admin;
                        new bootstrap.Modal(document.getElementById('editUserModal')).show();
                    });
            });
        });

        // Submit edición
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('edit_user_id').value;
            var errorsDiv = document.getElementById('editUserErrors');
            errorsDiv.classList.add('d-none');

            var data = {
                name: document.getElementById('edit_name').value,
                email: document.getElementById('edit_email').value,
                is_admin: document.getElementById('edit_is_admin').checked ? 1 : 0,
            };

            var password = document.getElementById('edit_password').value;
            if (password) data.password = password;

            fetch('/admin/users/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(function(r) {
                if (!r.ok) return r.json().then(function(d) { throw d; });
                return r.json();
            })
            .then(function() {
                window.location.reload();
            })
            .catch(function(err) {
                if (err.errors) {
                    var msgs = Object.values(err.errors).flat().join('<br>');
                    errorsDiv.innerHTML = msgs;
                    errorsDiv.classList.remove('d-none');
                }
            });
        });

        // Eliminar usuario
        document.querySelectorAll('.btn-delete-user').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                document.getElementById('delete_user_name').textContent = this.dataset.name;
                document.getElementById('deleteUserForm').action = '/admin/users/' + id;
                new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
            });
        });
    });
    </script>
</x-app-layout>
