@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('users.index') }}">/ Usuarios</a> / <span>Crear</span>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-header">
            Formulario de Registro
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

            <form id="userForm" action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="first_surname">Primer Apellido</label>
                    <input type="text" name="first_surname" id="first_surname" class="form-control" value="{{ old('first_surname') }}" required>
                </div>

                <div class="form-group">
                    <label for="second_surname">Segundo Apellido</label>
                    <input type="text" name="second_surname" id="second_surname" class="form-control" value="{{ old('second_surname') }}">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control" required style="height: 45px;">
                        <option value="">Selecciona un rol</option>
                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Organizador</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
                    Registrar
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
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de registrar un nuevo usuario con los siguientes datos:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Primer Apellido:</strong> <span id="modalFirstSurname"></span></li>
                    <li><strong>Segundo Apellido:</strong> <span id="modalSecondSurname"></span></li>
                    <li><strong>Correo Electrónico:</strong> <span id="modalEmail"></span></li>
                    <li><strong>Teléfono:</strong> <span id="modalPhone"></span></li>
                    <li><strong>Rol:</strong> <span id="modalRole"></span></li>
                </ul>
                <p>¿Estás seguro de que deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar datos en el modal cuando se hace clic en "Registrar"
        const nameInput = document.getElementById('name');
        const firstSurnameInput = document.getElementById('first_surname');
        const secondSurnameInput = document.getElementById('second_surname');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const roleInput = document.getElementById('role');

        const modalName = document.getElementById('modalName');
        const modalFirstSurname = document.getElementById('modalFirstSurname');
        const modalSecondSurname = document.getElementById('modalSecondSurname');
        const modalEmail = document.getElementById('modalEmail');
        const modalPhone = document.getElementById('modalPhone');
        const modalRole = document.getElementById('modalRole');

        document.querySelector('[data-target="#confirmModal"]').addEventListener('click', function() {
            modalName.textContent = nameInput.value;
            modalFirstSurname.textContent = firstSurnameInput.value;
            modalSecondSurname.textContent = secondSurnameInput.value;
            modalEmail.textContent = emailInput.value;
            modalPhone.textContent = phoneInput.value;
            modalRole.textContent = roleInput.options[roleInput.selectedIndex].text;
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('userForm').submit();
        });
    });
</script>

@endsection
