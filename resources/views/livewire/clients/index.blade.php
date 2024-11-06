@extends('layouts.app')

@section('breadcrumbs')
    / Clientes
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
        <h1 class="h3 text-primary"><i class="fas fa-users"></i> Lista de Clientes</h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal">
                <i class="fas fa-user-plus"></i> Registrar Cliente
            </button>
            <a href="{{ route('clients.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>
        </div>
    </div>

    <div class="card shadow-custom border-custom">
        <div class="card-header card-header-custom">
            <i class="fas fa-store-alt"></i> Clientes Registrados
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar cliente..." value="{{ request('search') }}">
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
                            <th scope="col"><i class="fas fa-cogs"></i> Teléfono</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Estado</th>
                            <th scope="col"><i class="fas fa-id-badge"></i> ID Cliente</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
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
                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editClientModal" 
                                        data-user-id="{{ $user->id }}" 
                                        data-user-name="{{ $user->name }}" 
                                        data-user-last-name="{{ $user->last_name }}" 
                                        data-user-second-last-name="{{ $user->second_last_name }}" 
                                        data-user-email="{{ $user->email }}" 
                                        data-user-phone="{{ $user->phone }}">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>

                                    <button type="button" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-status="{{ $user->status }}">
                                        <i class="fas {{ $user->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
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

<!-- Modal de Crear Cliente -->
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel"><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <!-- Campos de contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Cliente -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel"><i class="fas fa-user-edit"></i> Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.update', 'client_id_placeholder') }}" method="POST" id="editClientForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_second_last_name" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="edit_second_last_name" name="second_last_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Cambiar Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel"><i class="fas fa-cogs"></i> Cambiar Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas cambiar el estado del cliente <span id="clientName"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmStatusChange">Cambiar Estado</button>
            </div>
        </div>
    </div>
</div>
@endsection
