<!-- resources/views/sales/show.blade.php -->

@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Detalles de la Venta</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-receipt"></i> Detalles de la Venta #{{ $sale->id }}</h1>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Ventas
        </a>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-receipt"></i> Información de la Venta
        </div>
        <div class="card-body">
            <p><strong>Vendedor:</strong> {{ $sale->user ? $sale->user->name : 'Desconocido' }}</p>
            <p><strong>Cliente:</strong> {{ $sale->customer ? $sale->customer->name : 'Desconocido' }}</p>
            <p><strong>Monto Total:</strong> {{ $sale->total_amount }} Bs</p>
            <p><strong>Estado:</strong> {{ ucfirst($sale->status) }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>

            <h3 class="mt-4">Detalles de los Productos</h3>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-box"></i> Producto</th>
                            <th scope="col"><i class="fas fa-hashtag"></i> Cantidad</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Precio</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->price }} Bs</td>
                                <td>{{ $detail->total }} Bs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
