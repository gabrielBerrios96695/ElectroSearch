<!-- resources/views/livewire/products/create.blade.php -->

@extends('layouts.app')

@section('breadcrumbs')
    / Productos / Crear
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Producto</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-box"></i> Nuevo Producto
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" id="precio" name="precio" class="form-control" step="0.01" value="{{ old('precio') }}" required>
                    @error('precio')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" id="imagen" name="imagen" class="form-control" required>
                    @error('imagen')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" id="categoria" name="categoria" class="form-control" value="{{ old('categoria') }}" required>
                    @error('categoria')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="store_id" class="form-label">Tienda</label>
                    <select id="store_id" name="store_id" class="form-control" required>
                        <option value="">Seleccione una tienda</option>
                        @foreach(App\Models\Store::all() as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('store_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Producto
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
