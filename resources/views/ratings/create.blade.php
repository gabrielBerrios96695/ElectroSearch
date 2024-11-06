@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">Crear Calificación</h1>
@endsection

@section('content')
<div class="container">
    <h2>Crear Calificación para tus Productos</h2>

    <form action="{{ route('ratings.store') }}" method="POST">
        @csrf

        <!-- Seleccionar Venta -->
        <div class="mb-3">
            <label for="sale_id" class="form-label">Seleccionar Venta</label>
            <select name="sale_id" id="sale_id" class="form-select" required>
                <option value="">Seleccione una venta</option>
                @foreach($sales as $sale)
                    <option value="{{ $sale->id }}">{{ $sale->id }} - {{ $sale->created_at->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>

        <!-- Calificaciones por Producto -->
        <div class="mb-3">
            <label class="form-label">Calificar Productos</label>
            <div id="product-ratings">
                <!-- Aquí se cargarán dinámicamente los productos de la venta seleccionada -->
            </div>
        </div>

        <!-- Botón para Enviar -->
        <button type="submit" class="btn btn-primary">Enviar Calificaciones</button>
    </form>
</div>

@push('scripts')
<script>
    // Función para cargar los productos relacionados a la venta seleccionada
    document.getElementById('sale_id').addEventListener('change', function () {
        const saleId = this.value;
        const productRatingsDiv = document.getElementById('product-ratings');
        
        if (saleId) {
            fetch(`/sale/${saleId}/details`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    data.forEach(detail => {
                        html += `
                            <div class="mb-3">
                                <label class="form-label">${detail.product_name}</label>
                                
                                <!-- Calificación con Estrellas -->
                                <div class="stars" data-product-id="${detail.id}">
                                    <input type="radio" name="ratings[${detail.id}]" value="1" id="star1-${detail.id}" class="rating-input">
                                    <label for="star1-${detail.id}" class="rating-star">★</label>

                                    <input type="radio" name="ratings[${detail.id}]" value="2" id="star2-${detail.id}" class="rating-input">
                                    <label for="star2-${detail.id}" class="rating-star">★</label>

                                    <input type="radio" name="ratings[${detail.id}]" value="3" id="star3-${detail.id}" class="rating-input">
                                    <label for="star3-${detail.id}" class="rating-star">★</label>

                                    <input type="radio" name="ratings[${detail.id}]" value="4" id="star4-${detail.id}" class="rating-input">
                                    <label for="star4-${detail.id}" class="rating-star">★</label>

                                    <input type="radio" name="ratings[${detail.id}]" value="5" id="star5-${detail.id}" class="rating-input">
                                    <label for="star5-${detail.id}" class="rating-star">★</label>
                                </div>

                                <!-- Comentario -->
                                <textarea class="form-control" name="comments[${detail.id}]" placeholder="Comentario (opcional)"></textarea>
                            </div>
                        `;
                    });
                    productRatingsDiv.innerHTML = html;
                });
        } else {
            productRatingsDiv.innerHTML = '';
        }
    });
</script>

<!-- Estilos para las estrellas -->
<style>
    .rating-input {
        display: none;
    }

    .rating-star {
        font-size: 2rem;
        cursor: pointer;
        color: #d3d3d3;
    }

    /* Cambio para pintar de izquierda a derecha */
    .rating-star:hover,
    .rating-input:checked ~ .rating-star,
    .rating-input:checked + .rating-star {
        color: #f39c12;
    }

    .stars {
        display: inline-block;
    }
</style>
@endpush
@endsection
