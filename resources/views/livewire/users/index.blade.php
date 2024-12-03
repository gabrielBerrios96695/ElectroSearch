@extends('layouts.app')

@section('breadcrumbs')
    / Empleados
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1 class="h3 text-primary"><i class="fas fa-users"></i> Lista de Empleados</h1>

        @if (auth()->user()->role == 1) <!-- Solo mostrar los botones si el usuario es Administrador -->
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-user-plus"></i> Registrar Nuevo Empleados
                </button>
                <a href="{{ route('users.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar
                </a>
            </div>
        @endif
    </div>

    <div class="card shadow-custom border-custom">
        <div class="card-header card-header-custom">
            <i class="fas fa-store-alt"></i> Usuarios Registrados
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuarios..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead class="custom-bg-tertiary">
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-user"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-envelope"></i> Correo Electrónico</th>
                            <th scope="col"><i class="fas fa-user-tag"></i> Rol</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Teléfono</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Estado</th>
                            <th scope="col"><i class="fas fa-id-badge"></i> ID Usuario</th>
                            @if (auth()->user()->role == 1) <!-- Solo mostrar la columna de Acciones si es Administrador -->
                                <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->email }}
                                </td>

                                <td>
                                    @if ($user->role == 1)
                                        <i class="fas fa-user-shield"></i> Administrador
                                    @elseif ($user->role == 2)
                                        <i class="fas fa-user-tie"></i> Vendedor
                                    @endif
                                </td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status == 1 ? 'Habilitado' : 'Deshabilitado' }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional(User::find($user->userid))->name }}
                                </td>

                                @if (auth()->user()->role == 1) <!-- Solo mostrar las acciones si el usuario es Administrador -->
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-status="{{ $user->status }}">
                                            <i class="fas {{ $user->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->role == 1) <!-- Solo mostrar los modales si el usuario es Administrador -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Registrar Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear un nuevo usuario -->
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="second_last_name" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="second_last_name" name="second_last_name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="1">Administrador</option>
                            <option value="2">Vendedor</option>
                            <option value="3">Cliente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Habilitado</option>
                            <option value="0">Deshabilitado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal de Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para editar el usuario -->
                    <form method="POST" id="editUserForm" action="{{ route('users.update', ':id') }}">
                        @csrf
                        @method('PUT') <!-- Utilizamos PUT ya que estamos actualizando un recurso -->
                        
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Rol</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="1">Administrador</option>
                                <option value="2">Vendedor</option>
                                <option value="3">Cliente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Estado</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="1">Habilitado</option>
                                <option value="0">Deshabilitado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Cambiar Estado del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="toggleStatusForm" action="{{ route('users.toggleStatus', ['user' => 'user_id']) }}">
                    @csrf
                    @method('PATCH')
                    
                    <p id="toggleStatusMessage">¿Estás seguro de que deseas cambiar el estado de este usuario?</p>
                    
                    <!-- Hidden inputs to store user info -->
                    <input type="hidden" name="user_id" id="toggleStatusUserId">
                    <input type="hidden" name="status" id="toggleStatusValue">

                    <button type="submit" class="btn btn-primary" id="toggleStatusSubmit">Cambiar Estado</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#toggleStatusModal"]');
        
        statusButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                const userStatus = button.getAttribute('data-user-status');

                // Cambiar el texto del mensaje según el estado del usuario
                const statusMessage = document.getElementById('toggleStatusMessage');
                const submitButton = document.getElementById('toggleStatusSubmit');
                const statusValueField = document.getElementById('toggleStatusValue');
                const userIdField = document.getElementById('toggleStatusUserId');

                // Actualizar la URL del formulario para incluir el userId
                const formAction = "{{ route('users.toggleStatus', ['user' => 'user_id']) }}".replace('user_id', userId);
                document.getElementById('toggleStatusForm').action = formAction;

                userIdField.value = userId;
                
                if (userStatus == 1) { // Si el usuario está habilitado
                    statusMessage.textContent = `¿Estás seguro de que deseas deshabilitar a ${userName}?`;
                    statusValueField.value = 0; // Deshabilitar
                    submitButton.textContent = 'Deshabilitar Usuario';
                } else { // Si el usuario está deshabilitado
                    statusMessage.textContent = `¿Estás seguro de que deseas habilitar a ${userName}?`;
                    statusValueField.value = 1; // Habilitar
                    submitButton.textContent = 'Habilitar Usuario';
                }
            });
        });
    });
</script>
@endpush

@endsection
