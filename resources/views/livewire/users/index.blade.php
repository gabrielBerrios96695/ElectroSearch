@extends('layouts.app')

@section('breadcrumbs')
    / Usuarios
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-users"></i> Usuarios
        </div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Contenedor con scroll horizontal -->
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo Electrónico</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col">ID Usuario</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role == 'vendedor')
                                        Vendedor
                                    @elseif ($user->role == 'cliente')
                                        Cliente
                                    @else
                                        {{ ucfirst($user->role) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $user->status ? 'Habilitado' : 'Deshabilitado' }}
                                </td>
                                <td>
                                    {{ optional(User::find($user->userid))->name }} <!-- Muestra el nombre del usuario asociado al userId -->
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-status="{{ $user->status }}">
                                        <i class="fas fa-toggle-on"></i> {{ $user->status ? 'Deshabilitar' : 'Habilitar' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> al usuario <strong id="userName"></strong>? Esta acción cambiará el estado del usuario.
            </div>
            <div class="modal-footer">
                <form id="toggleStatusForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        toggleStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var userId = button.getAttribute('data-user-id'); // Extract info from data-* attributes
            var userName = button.getAttribute('data-user-name'); // Extract user name
            var userStatus = button.getAttribute('data-user-status'); // Extract user status
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/users/' + userId + '/toggleStatus'; // Set the form action to the correct route

            // Set the action in the modal body
            var actionText = userStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            // Set the user name in the modal body
            var userNameElement = document.getElementById('userName');
            userNameElement.textContent = userName;
        });
    });
</script>
@endpush
@endsection
