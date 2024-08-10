<!-- resources/views/livewire/products/edit.blade.php -->

@extends('layouts.app')

@section('breadcrumbs')
    / Productos / Editar
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Producto</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-box"></i> Editar Producto
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nombre del Producto -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $product->nombre) }}" required>
                    @error('nombre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required>{{ old('descripcion', $product->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" id="precio" name="precio" class="form-control" value="{{ old('precio', $product->precio) }}" step="0.01" required>
                    @error('precio')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Imagen -->
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" id="imagen" name="imagen" class="form-control">
                    @if ($product->imagen)
                        <img src="{{ asset('storage/images/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                    @endif
                    @error('imagen')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" id="categoria" name="categoria" class="form-control" value="{{ old('categoria', $product->categoria) }}" required>
                    @error('categoria')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Store ID -->
                <div class="mb-3">
                    <label for="store_id" class="form-label">ID de la Tienda</label>
                    <input type="number" id="store_id" name="store_id" class="form-control" value="{{ old('store_id', $product->store_id) }}" required>
                    @error('store_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Producto
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
