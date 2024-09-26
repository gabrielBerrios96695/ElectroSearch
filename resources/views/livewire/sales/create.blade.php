@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Venta</h1>

    <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
        @csrf

        <!-- Selección del cliente -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Cliente</label>
            <select id="customer_id" name="customer_id" class="form-select" required>
                <option value="">Selecciona un cliente</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Selección del producto -->
        <div class="mb-4">
            <label for="product-select" class="form-label"><i class="fas fa-box"></i> Agregar Producto</label>
            <div class="d-flex">
                <select id="product-select" class="form-select me-3">
                    <option value="">Selecciona un producto</option>
                    @foreach ($products as $product)
                        @if ($product->quantity > 0)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}" 
                                    data-quantity="{{ $product->quantity }}">
                                {{ $product->name }} (Disponible: {{ $product->quantity }})
                            </option>
                        @endif
                    @endforeach
                </select>
                <button type="button" id="add-product-btn" class="btn btn-secondary">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </div>
        </div>

        <!-- Tabla de productos -->
        <table id="products-table" class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila de productos agregados aquí -->
            </tbody>
        </table>

        <button type="button" id="confirm-sale-btn" class="btn btn-primary">Confirmar Venta</button>
    </form>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmSaleModal" tabindex="-1" aria-labelledby="confirmSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSaleModalLabel">Confirmar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Detalles de la venta:</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="sale-summary">
                        <!-- Resumen de productos aquí -->
                    </tbody>
                </table>
                <p><strong>Total de la venta: </strong><span id="total-sale-amount">0</span> Bs</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="submit-sale-btn" class="btn btn-primary">Confirmar Venta</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product-select');
    const addProductBtn = document.getElementById('add-product-btn');
    const productsTableBody = document.querySelector('#products-table tbody');
    const confirmSaleBtn = document.getElementById('confirm-sale-btn');
    const saleSummary = document.getElementById('sale-summary');
    const totalSaleAmount = document.getElementById('total-sale-amount');
    const submitSaleBtn = document.getElementById('submit-sale-btn');
    const saleForm = document.getElementById('sale-form');

    // Función para agregar productos a la tabla
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.text;
        const productPrice = selectedOption.getAttribute('data-price');
        const productQuantity = selectedOption.getAttribute('data-quantity');

        if (!productId) return;

        // Verificar si el producto ya está en la tabla
        const existingRow = Array.from(productsTableBody.rows).find(row => row.dataset.productId === productId);
        if (existingRow) {
            const quantityInput = existingRow.querySelector('input[name*="[quantity]"]');
            const newQuantity = parseInt(quantityInput.value) + 1;

            if (newQuantity <= productQuantity) {
                quantityInput.value = newQuantity;
            } else {
                alert('No puedes agregar más de la cantidad disponible');
            }

            return;
        }

        // Crear una nueva fila en la tabla de productos
        const row = document.createElement('tr');
        row.dataset.productId = productId;
        row.innerHTML = `
            <td>${productName}</td>
            <td>
                <input type="number" name="products[${productsTableBody.rows.length}][quantity]" 
                       value="1" min="1" max="${productQuantity}" 
                       class="form-control" required>
                <input type="hidden" name="products[${productsTableBody.rows.length}][id]" value="${productId}">
            </td>
            <td>${productPrice} Bs</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </td>
        `;

        productsTableBody.appendChild(row);

        // Limpiar selección
        productSelect.selectedIndex = 0;
    });

    // Eliminar productos de la tabla
    productsTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-product-btn')) {
            event.target.closest('tr').remove();
        }
    });

    // Mostrar modal de confirmación con el resumen de la venta
    confirmSaleBtn.addEventListener('click', function() {
        saleSummary.innerHTML = '';
        let total = 0;

        Array.from(productsTableBody.rows).forEach(row => {
            const productName = row.cells[0].textContent;
            const quantity = row.querySelector('input[name*="[quantity]"]').value;
            const price = parseFloat(row.cells[2].textContent.replace(' Bs', ''));
            const productTotal = price * quantity;

            total += productTotal;

            // Agregar productos al resumen
            saleSummary.innerHTML += `
                <tr>
                    <td>${productName}</td>
                    <td>${quantity}</td>
                    <td>${price.toFixed(2)} Bs</td>
                    <td>${productTotal.toFixed(2)} Bs</td>
                </tr>
            `;
        });

        // Mostrar total en el modal
        totalSaleAmount.textContent = total.toFixed(2);

        // Abrir el modal
        new bootstrap.Modal(document.getElementById('confirmSaleModal')).show();
    });

    // Confirmar la venta y enviar el formulario
    submitSaleBtn.addEventListener('click', function() {
        saleForm.submit();
    });
});
</script>
@endpush
@endsection
