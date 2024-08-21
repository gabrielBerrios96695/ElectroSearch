@extends('layouts.app')

@section('breadcrumbs')
    / Tipos de Puntos
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-green-800">Lista de Tipos de Puntos</h1>
        <a href="{{ route('type_points.create') }}" class="btn btn-success">
            Registrar Nuevo Tipo de Punto
        </a>
    </div>

    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <i class="fas fa-map-marker-alt"></i> Tipos de Puntos
        </div>
        <div class="card-body bg-light">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">
                                <a href="{{ route('type_points.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    Nro.
                                    @if ($sortField == 'id')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('type_points.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    Nombre
                                    @if ($sortField == 'name')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">Descripción</th> 
                            <th scope="col">
                                <a href="{{ route('type_points.index', ['sort_field' => 'userid', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}" >
                                    ID Usuario
                                    @if ($sortField == 'userid')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('type_points.index', ['sort_field' => 'status', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                    Estado
                                    @if ($sortField == 'status')
                                        <i class="fas fa-sort-{{ $sortDirection }}"></i>
                                    @endif
                                </a>
                            </th>
                            
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($typePoints as $typePoint)
                            <tr>
                                <th scope="row">{{ $typePoint->id }}</th>
                                <td>{{ $typePoint->name }}</td>
                                <td>{{ $typePoint->description }}</td>
                                <td>
                                    {{ optional(User::find($typePoint->userid))->name }}
                                </td>
                                <td>
                                    <span class="badge {{ $typePoint->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $typePoint->status == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('type_points.edit', $typePoint->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn {{ $typePoint->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-type-point-id="{{ $typePoint->id }}" data-type-point-name="{{ $typePoint->name }}" data-type-point-status="{{ $typePoint->status }}">
                                        <i class="fas {{ $typePoint->status == 1 ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                        {{ $typePoint->status == 1 ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $typePoints->appends(['sort_field' => $sortField, 'sort_direction' => $sortDirection])->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> el tipo de punto <strong id="typePointName"></strong>? Esta acción cambiará el estado del tipo de punto.
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
            var typePointId = button.getAttribute('data-type-point-id'); 
            var typePointName = button.getAttribute('data-type-point-name'); 
            var typePointStatus = button.getAttribute('data-type-point-status'); 
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/type_points/' + typePointId + '/toggleStatus';

            var actionText = typePointStatus == 1 ? 'desactivar' : 'activar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            var typePointNameElement = document.getElementById('typePointName');
            typePointNameElement.textContent = typePointName;
        });
    });
</script>
@endpush
@endsection
