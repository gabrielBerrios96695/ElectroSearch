
@extends('layouts.app')

@section('breadcrumbs')
    / Productos
@endsection

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Productos</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Nuevo Producto
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-box"></i> Productos
        </div>
        <div class="card-body">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <th scope="row">{{ $product->id }}</th>
                            <td>{{ $product->nombre }}</td>
                            <td>{{ $product->descripcion }}</td>
                            <td>{{ $product->precio }}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="product-image">
                            </td>
                            <td>{{ $product->categoria }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button wire:click="delete({{ $product->id }})" class="btn btn-danger">
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

@endsection
