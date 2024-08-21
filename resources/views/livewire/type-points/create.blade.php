@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('type_points.index') }}">/ Tipos de Puntos</a> / <span>Crear</span>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Tipo de Punto</h1>
        <a href="{{ route('type_points.index') }}" class="btn btn-secondary">Volver</a>
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

            <form id="typePointForm" action="{{ route('type_points.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
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
                <p>Estás a punto de registrar un nuevo tipo de punto con los siguientes datos:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Descripción:</strong> <span id="modalDescription"></span></li>
                    <li><strong>Estado:</strong> <span id="modalStatus"></span></li>
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
        const descriptionInput = document.getElementById('description');
        const statusInput = document.getElementById('status');

        const modalName = document.getElementById('modalName');
        const modalDescription = document.getElementById('modalDescription');
        const modalStatus = document.getElementById('modalStatus');

        document.querySelector('[data-target="#confirmModal"]').addEventListener('click', function() {
            modalName.textContent = nameInput.value;
            modalDescription.textContent = descriptionInput.value;
            modalStatus.textContent = statusInput.options[statusInput.selectedIndex].text;
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('typePointForm').submit();
        });
    });
</script>

@endsection
