@extends('layouts.app')

@section('breadcrumbs')
    / Usuarios
@endsection

@section('content')
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
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Rol</th>
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
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar al usuario <strong id="userName"></strong>? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var storeId = button.getAttribute('data-store-id'); // Extract info from data-* attributes
            var storeName = button.getAttribute('data-store-name'); // Extract store name
            var form = deleteModal.querySelector('#deleteForm');
            form.action = '/stores/' + storeId; // Set the form action to the correct route

            // Set the store name in the modal body
            var storeNameElement = document.getElementById('storeName');
            storeNameElement.textContent = storeName;
        });
    });
</script>
@endpush
@endsection
