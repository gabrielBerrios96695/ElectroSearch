@extends('layouts.app')

@section('breadcrumbs')
    / Tiendas
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Tiendas</h1>
        <a href="{{ route('store.create') }}" class="btn btn-primary">
            <i class="fas fa-store"></i> Registrar Nueva Tienda
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-store-alt"></i> Tiendas
        </div>
        <div class="card-body">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Latitud</th>
                        <th scope="col">Longitud</th>
                        @if (Auth::user()->isAdmin())
                            <th scope="col">Estado</th>
                            <th scope="col">Registrado/Actualizado</th>
                        @endif
                        <th scope="col">Acciones</th>
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
                                <td>{{ $store->status ? 'Habilitado' : 'Deshabilitado' }}</td>
                                <td>{{ $store->created_by }}</td>
                            @endif
                            <td>
                                <a href="{{ route('store.edit', $store->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @if (Auth::user()->isAdmin())
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-store-id="{{ $store->id }}" data-store-name="{{ $store->name }}" data-store-status="{{ $store->status }}">
                                        <i class="fas fa-toggle-on"></i> {{ $store->status ? 'Deshabilitar' : 'Habilitar' }}
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

<!-- Modal de Confirmación -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirmar Cambio de Estado</h5>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
