@extends('layouts.app')

@section('breadcrumbs')
    / Categorías / Editar
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Categoría</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list-alt"></i> Editar Categoría
        </div>
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre de la Categoría -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Categoría</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Categoría
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
