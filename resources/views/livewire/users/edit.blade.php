@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('users.index') }}">/ Usuarios</a> / <a>Editar</a>
@endsection

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
    </div>
    
    <div class="card">
        <div class="card-header">
            Formulario de Edición de Usuario
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="userForm" action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Primer Apellido</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="second_last_name">Segundo Apellido</label>
                    <input type="text" name="second_last_name" id="second_last_name" value="{{ old('second_last_name', $user->second_last_name) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control" required style="height: 45px;">
                        <option value="">Selecciona un rol</option>
                        <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Vendedor</option>
                        <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control" required>
                </div>

                <button type="button" class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Actualizar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Actualización</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de actualizar los datos del usuario con los siguientes detalles:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Primer Apellido:</strong> <span id="modalLastName"></span></li>
                    <li><strong>Segundo Apellido:</strong> <span id="modalSecondLastName"></span></li>
                    <li><strong>Correo Electrónico:</strong> <span id="modalEmail"></span></li>
                    <li><strong>Rol:</strong> <span id="modalRole"></span></li>
                    <li><strong>Teléfono:</strong> <span id="modalPhone"></span></li>
                </ul>
                <p>¿Estás seguro de que deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar datos en el modal cuando se hace clic en "Actualizar"
        const nameInput = document.getElementById('name');
        const lastNameInput = document.getElementById('last_name');
        const secondLastNameInput = document.getElementById('second_last_name');
        const emailInput = document.getElementById('email');
        const roleInput = document.getElementById('role');
        const phoneInput = document.getElementById('phone');

        const modalName = document.getElementById('modalName');
        const modalLastName = document.getElementById('modalLastName');
        const modalSecondLastName = document.getElementById('modalSecondLastName');
        const modalEmail = document.getElementById('modalEmail');
        const modalRole = document.getElementById('modalRole');
        const modalPhone = document.getElementById('modalPhone');

        // Agregar evento para abrir el modal y cargar los datos
        document.querySelector('[data-bs-target="#confirmModal"]').addEventListener('click', function() {
            modalName.textContent = nameInput.value;
            modalLastName.textContent = lastNameInput.value;
            modalSecondLastName.textContent = secondLastNameInput.value;
            modalEmail.textContent = emailInput.value;
            modalRole.textContent = roleInput.options[roleInput.selectedIndex].text;
            modalPhone.textContent = phoneInput.value;
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('userForm').submit();
        });
    });
</script>
@endpush
@endsection
