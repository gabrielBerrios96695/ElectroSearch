@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Detalles de la Venta</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-receipt"></i> Detalles de la 
            @if($sale->type_of_sale == 1)
                Venta #{{ $sale->id }}
            @else
                Compra #{{ $sale->id }}
            @endif
        </h1>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de 
            @if($sale->type_of_sale == 1)
                Ventas
            @else
                Compras
            @endif
        </a>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-receipt"></i> Información de la 
            @if($sale->type_of_sale == 1)
                Venta
            @else
                Compra
            @endif
        </div>
        <div class="card-body">
        <p><strong>Vendedor:</strong> 
    @if($sale->user && $sale->customer && $sale->user->id == $sale->customer->id)
        Pendiente
    @else
        {{ $sale->user ? $sale->user->name . ' ' . $sale->user->last_name . ' ' . ($sale->user->second_last_name ?? '') : 'Desconocido' }}
    @endif
</p>

<p><strong>Cliente:</strong> 
    {{ $sale->customer ? $sale->customer->name . ' ' . $sale->customer->last_name . ' ' . ($sale->customer->second_last_name ?? '') : 'Desconocido' }}
</p>

            <p><strong>Monto Total:</strong> {{ $sale->total_amount }} Bs</p>
            <p><strong>Estado:</strong> {{ ucfirst($sale->status) }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>

            @if($sale->type_of_sale == 0 && $sale->status != 'completed' && auth()->user()->role != 3 && $sale->status != 'cancelled')
                <!-- Botón para abrir el modal de confirmación, solo si no está completada -->
                <div class="position-relative">
                    <button class="btn btn-primary position-absolute" style="top: 10px; right: 10px;" data-toggle="modal" data-target="#confirmModal">
                        Confirmar Compra
                    </button>
                </div>
            @endif
            <button class="btn btn-warning" onclick="window.location='{{ route('sales.receipt', $sale->id) }}'">
    <i class="fas fa-file-pdf"></i> Generar Recibo PDF
</button>

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

<!-- Modal de Confirmación de Compra -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas confirmar esta compra? Se asignará tu nombre como vendedor, esta accion no se puede desahacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('sales.confirm', $sale->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <button type="submit" class="btn btn-primary">Confirmar Compra</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

function printReceipt() {
    var printContent = document.getElementById("receiptContent").innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}
</script>
<!-- Agregar los scripts necesarios para que el modal funcione correctamente -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
