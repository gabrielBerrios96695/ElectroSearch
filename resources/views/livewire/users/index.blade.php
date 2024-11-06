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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1 class="h3 text-primary"><i class="fas fa-users"></i> Lista de Usuarios</h1>

        @if (auth()->user()->role == 1) <!-- Solo mostrar los botones si el usuario es Administrador -->
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
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
                                <th scope="row">{{ $user->id }}</th>
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
    <!-- Modal de Crear Usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <!-- Contenido del modal -->
    </div>

    <!-- Modal de Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <!-- Contenido del modal -->
    </div>

    <!-- Modal de Cambio de Estado -->
    <div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
        <!-- Contenido del modal -->
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tu código JavaScript para manejar los modales
    });
</script>
@endpush
@endsection
