@extends('layouts.app')

@section('breadcrumbs')
    / Productos
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Productos</h1>
        
        <div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Nuevo Producto
        </a>
        <a href="{{ route('products.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-box"></i> Productos
        </div>
        <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar productos..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio </th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <th scope="row">{{ $product->id }}</th>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->quantity}}</td>
                            <td>{{ $product->price }} Bs.</td>
                            <td class="text-center">
                                @if($product->image)
                                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" style="max-width: 150px; max-height: 120px;">

                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->category->name ?? 'Sin Categoría' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modifyStockModal" 
                                    data-product-id="{{ $product->id }}" data-current-stock="{{ $product->quantity }}">
                                    <i class="fas fa-cogs"></i>
                                </button>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este producto?
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
<!-- Modal de Modificar Stock -->
<div class="modal fade" id="modifyStockModal" tabindex="-1" aria-labelledby="modifyStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyStockModalLabel"><i class="fas fa-box"></i> Modificar Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="modifyStockForm" method="POST" action="{{ route('products.updateStock', ['id' => $product->id]) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_stock" class="form-label">Stock Actual</label>
                        <input type="number" id="current_stock" class="form-control" value="0" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="new_stock" class="form-label">Nuevo Stock</label>
                        <input type="number" id="new_stock" class="form-control" name="new_stock" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var productId = button.getAttribute('data-product-id');
            var form = document.getElementById('deleteForm');
            form.action = '/products/' + productId;
        });
        var modifyStockModal = document.getElementById('modifyStockModal');
    
        modifyStockModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var productId = button.getAttribute('data-product-id');
            var currentStock = button.getAttribute('data-current-stock');
            
            // Rellenar el formulario con la cantidad actual de stock
            document.getElementById('current_stock').value = currentStock;
            document.getElementById('new_stock').value = currentStock;
            
            // Actualizar la acción del formulario para enviar la solicitud al producto correcto
            var form = document.getElementById('modifyStockForm');
            form.action = '/products/' + productId + '/updateStock'; // Ruta para actualizar stock
        });
    });
</script>

@endsection
