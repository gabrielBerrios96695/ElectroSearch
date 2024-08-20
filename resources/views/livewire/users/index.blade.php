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
        <h1 class="h3 text-primary"><i class="fas fa-users"></i> Lista de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
        </a>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-users"></i> Usuarios
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-hashtag"></i> Nro.
                                    @if ($sortField == 'id')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-user"></i> Nombre Completo
                                    @if ($sortField == 'name')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'email', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-envelope"></i> Correo Electrónico
                                    @if ($sortField == 'email')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'role', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-user-tag"></i> Rol
                                    @if ($sortField == 'role')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'phone', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-cogs"></i> Teléfono
                                    @if ($sortField == 'phone')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'status', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-cogs"></i> Estado
                                    @if ($sortField == 'status')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('users.index', ['sort_field' => 'userid', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="fas fa-id-badge"></i> ID Usuario
                                    @if ($sortField == 'userid')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }} {{ $user->first_surname }} {{ $user->second_surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role == 1)
                                        <i class="fas fa-user-shield"></i> Administrador
                                    @elseif ($user->role == 2)
                                        <i class="fas fa-user-tie"></i> Organizador
                                    @elseif ($user->role == 3)
                                        <i class="fas fa-user"></i> Usuario
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
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-status="{{ $user->status }}">
                                        <i class="fas {{ $user->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $user->status ? '' : '' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(['sort_field' => $sortField, 'sort_direction' => $sortDirection])->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="toggleStatusModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Cambio de Estado</h5>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        toggleStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var userId = button.getAttribute('data-user-id'); 
            var userName = button.getAttribute('data-user-name'); 
            var userStatus = button.getAttribute('data-user-status'); 
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/users/' + userId + '/toggleStatus';

            var actionText = userStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            var userNameElement = document.getElementById('userName');
            userNameElement.textContent = userName;
        });
    });
</script>
@endpush
@endsection
