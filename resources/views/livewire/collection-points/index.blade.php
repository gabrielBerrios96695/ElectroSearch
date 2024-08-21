@extends('layouts.app')

@section('breadcrumbs')
    / Puntos de Recolección
@endsection

@section('content')
@php
    use App\Models\User;
@endphp
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3"><i class="fas fa-trash"></i> Lista de Puntos de Recolección</h1>
        <a href="{{ route('collection_points.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Nuevo Punto de Recolección
        </a>
    </div>

    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <i class="fas fa-trash"></i> Puntos de Recolección Registrados
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover"">
                    <thead class="custom-bg-tertiary">
                        <tr>
                            <th scope="col">Nro.</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Latitud</th>
                            <th scope="col">Longitud</th>
                            <th scope="col">Apertura</th>
                            <th scope="col">Cierre</th>
                            <th scope="col">Tipo de Punto</th>
                            @if (Auth::user()->isAdmin())
                                <th scope="col">Estado</th>
                            @endif
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collectionPoints as $point)
                            <tr>
                                <th scope="row">{{ $point->id }}</th>
                                <td>{{ $point->name }}</td>
                                <td>{{ $point->latitude }}</td>
                                <td>{{ $point->longitude }}</td>
                                <td>{{ $point->opening_time }}</td>
                                <td>{{ $point->closing_time }}</td>
                                <td>{{ $point->typePoint->name }}</td>
                              
                                @if (Auth::user()->isAdmin())
                                    <td>
                                        <span class="badge {{ $point->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $point->status == 1 ? 'Habilitado' : 'Deshabilitado' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    <a href="{{ route('collection_points.edit', $point->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if (Auth::user()->isAdmin())
                                        <button type="button" class="btn {{ $point->status == 1 ? 'btn-danger btn-sm' : 'btn-success btn-sm' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-point-id="{{ $point->id }}" data-point-name="{{ $point->name }}" data-point-status="{{ $point->status }}">
                                            <i class="fas {{ $point->status == 1 ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $point->status == 1 ? 'Deshabilitar' : 'Habilitar' }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-custom">
            <div class="modal-header custom-bg-warning text-white">
                <h5 class="modal-title" id="toggleStatusModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> el punto de recolección <strong id="pointName"></strong>? Esta acción cambiará el estado del punto de recolección.
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
            var button = event.relatedTarget; // Button that triggered the modal
            var pointId = button.getAttribute('data-point-id'); // Extract info from data-* attributes
            var pointName = button.getAttribute('data-point-name'); // Extract point name
            var pointStatus = button.getAttribute('data-point-status'); // Extract point status
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/collection_points/' + pointId + '/toggleStatus'; // Set the form action to the correct route

            // Set the action in the modal body
            var actionText = pointStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            // Set the point name in the modal body
            var pointNameElement = document.getElementById('pointName');
            pointNameElement.textContent = pointName;
        });
    });
</script>
@endpush
@endsection
