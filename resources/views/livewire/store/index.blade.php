@extends('layouts.app')

@section('breadcrumbs')
    Tiendas/
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
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Latitud</th>
                        <th scope="col">Longitud</th>
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
                            <td>
                                <a href="{{ route('store.edit', $store->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-store-id="{{ $store->id }}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta tienda? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var storeId = button.getAttribute('data-store-id'); // Extract info from data-* attributes
            var form = deleteModal.querySelector('#deleteForm');
            form.action = '/stores/' + storeId; // Set the form action to the correct route
        });
    });
</script>
@endpush
@endsection
