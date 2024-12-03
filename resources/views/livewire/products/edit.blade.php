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
                    <label for="name" class="form-label">Nombre del Producto</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Precio -->
                <div class="mb-3 position-relative">
    <label for="price" class="form-label">Precio</label>
    <div class="input-group">
        <input 
            type="number" 
            id="price" 
            name="price" 
            class="form-control" 
            value="{{ old('price', $product->price) }}" 
            step="0.01" 
            required
        >
        <span class="input-group-text">Bs</span>
    </div>
    @error('price')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>


                <!-- Imagen -->
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen</label>
                    <input type="file" id="image" name="image" class="form-control">
                    @if ($product->image)
                        <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                    @endif
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
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
