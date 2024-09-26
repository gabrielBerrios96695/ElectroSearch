@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Ventas</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-danger"><i class="fas fa-shopping-cart"></i> Lista de Ventas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Nueva Venta
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-shopping-cart"></i> Ventas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> ID</th>
                            <th scope="col"><i class="fas fa-user"></i> Vendedor</th>
                            <th scope="col"><i class="fas fa-user"></i> Cliente</th>
                            <th scope="col"><i class="fas fa-money-bill"></i> Monto Total</th>
                            <th scope="col"><i class="fas fa-info-circle"></i> Estado</th>
                            <th scope="col"><i class="fas fa-calendar-alt"></i> Fecha de Creación</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <th scope="row">{{ $sale->id }}</th>
                                <td>{{ $sale->user ? $sale->user->name : 'Desconocido' }}</td>
                                <td>{{ $sale->customer ? $sale->customer->name : 'Desconocido' }}</td>
                                <td>{{ $sale->total_amount }} Bs</td>
                                <td>
                                    @if ($sale->status == 'completed')
                                        <span class="badge bg-success">Completada</span>
                                    @elseif ($sale->status == 'pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <!-- Acción Ver Detalles -->
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Acción Editar (solo si la venta no está completada) -->

                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>


                                    <!-- Acción Eliminar -->
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteSaleModal"
                                            data-sale-id="{{ $sale->id }}"
                                            data-sale-amount="{{ $sale->total_amount }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Eliminación -->
<div class="modal fade" id="deleteSaleModal" tabindex="-1" aria-labelledby="deleteSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="deleteSaleModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar la venta de <strong id="saleAmount"></strong> Bs con ID <strong id="saleId"></strong>? Esta acción no puede deshacerse.
            </div>
            <div class="modal-footer">
                <form id="deleteSaleForm" action="" method="POST">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteSaleModal = document.getElementById('deleteSaleModal');
        deleteSaleModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que abrió el modal
            var saleId = button.getAttribute('data-sale-id'); // ID de la venta
            var saleAmount = button.getAttribute('data-sale-amount'); // Monto total de la venta

            // Actualizar el texto del modal
            var saleIdElement = document.getElementById('saleId');
            var saleAmountElement = document.getElementById('saleAmount');
            saleIdElement.textContent = saleId;
            saleAmountElement.textContent = saleAmount;

            // Configurar la acción del formulario de eliminación
            var form = deleteSaleModal.querySelector('#deleteSaleForm');
            form.action = '/sales/' + saleId;
        });
    });
</script>
@endpush
@endsection
