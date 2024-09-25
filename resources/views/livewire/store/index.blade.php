@extends('layouts.app')

@section('breadcrumbs')
    / Tiendas
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3"><i class="fas fa-store-alt"></i> Lista de Tiendas</h1>
        <div>
            <a href="{{ route('store.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Nueva Tienda
            </a>
            <a href="{{ route('store.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar
                </a>
        </div>
    </div>

    <div class="card shadow-custom border-custom">
        <div class="card-header card-header-custom">
            <i class="fas fa-store-alt"></i> Tiendas Registradas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead class="custom-bg-tertiary">
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-store"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-map-marker-alt"></i> Latitud</th>
                            <th scope="col"><i class="fas fa-map-marker-alt"></i> Longitud</th>
                            @if (Auth::user()->isAdmin())
                                <th scope="col"><i class="fas fa-toggle-on"></i> Estado</th>
                                <th scope="col"><i class="fas fa-calendar-alt"></i> Registrado/Actualizado</th>
                            @endif
                            <th scope="col"><i class="fas fa-tools"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $store)
                            <tr>
                                <th scope="row">{{ $store->id }}</th>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->latitude }}</td>
                                <td>{{ $store->longitude }}</td>
                                @if (Auth::user()->isAdmin())
                                    <td>
                                        <span class="badge {{ $store->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $store->status == 1 ? 'Habilitado' : 'Deshabilitado' }}
                                        </span>
                                    </td>
                                    <td>{{ $store->created_by }}</td>
                                @endif
                                <td>
                                    <a href="{{ route('store.edit', $store->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if (Auth::user()->isAdmin())
                                        <button type="button" class="btn {{ $store->status == 1 ? 'btn-danger btn-sm' : 'btn-success btn-sm' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-store-id="{{ $store->id }}" data-store-name="{{ $store->name }}" data-store-status="{{ $store->status }}">
                                            <i class="fas {{ $store->status == 1 ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $store->status == 1 ? '' : '' }}
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
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> la tienda <strong id="storeName"></strong>? Esta acción cambiará el estado de la tienda.
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
            var storeId = button.getAttribute('data-store-id'); // Extract info from data-* attributes
            var storeName = button.getAttribute('data-store-name'); // Extract store name
            var storeStatus = button.getAttribute('data-store-status'); // Extract store status
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/stores/' + storeId + '/toggleStatus'; // Set the form action to the correct route

            // Set the action in the modal body
            var actionText = storeStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            // Set the store name in the modal body
            var storeNameElement = document.getElementById('storeName');
            storeNameElement.textContent = storeName;
        });
    });
</script>
@endpush
@endsection