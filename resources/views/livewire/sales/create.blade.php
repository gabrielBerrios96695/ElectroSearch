@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Venta</h1>

    <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
        @csrf

        <!-- Selección del cliente -->
        <div class="mb-3 row">
            <label for="customer_id" class="form-label">Cliente</label>
            <div class="col-md-8">
                <select id="customer_id" name="customer_id" class="form-select" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="customer_filter" class="form-control" placeholder="Escribe para filtrar" oninput="filterCustomers()">
                </div>
            </div>
        </div>

        <!-- Selección del producto -->
        <div class="mb-4 row">
            <label for="product-select" class="form-label"><i class="fas fa-box"></i> Agregar Producto</label>
            <div class="col-md-8">
                <select id="product-select" class="form-select" required>
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
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="product_filter" class="form-control" placeholder="Escribe para filtrar" oninput="filterProducts()">
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" id="add-product-btn" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Agregar Producto
            </button>
        </div>

        <!-- Tabla de productos -->
        <table id="products-table" class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
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

    // Función para filtrar clientes
    window.filterCustomers = function() {
        const input = document.getElementById('customer_filter').value.toLowerCase();
        const select = document.getElementById('customer_id');
        const options = select.options;

        for (let i = 1; i < options.length; i++) { // Comienza desde 1 para omitir la opción predeterminada
            const option = options[i];
            const text = option.text.toLowerCase();

            option.style.display = text.includes(input) ? 'block' : 'none'; // Muestra u oculta opciones
        }

        // Seleccionar el primer cliente que coincida si hay resultados
        for (let i = 1; i < options.length; i++) {
            if (options[i].style.display === 'block') {
                select.selectedIndex = i; // Selecciona la primera opción visible
                return;
            }
        }

        // Si no hay coincidencias, limpia el select
        select.selectedIndex = 0;
    };

    // Función para filtrar productos
    window.filterProducts = function() {
        const input = document.getElementById('product_filter').value.toLowerCase();
        const select = document.getElementById('product-select');
        const options = select.options;

        for (let i = 1; i < options.length; i++) { // Comienza desde 1 para omitir la opción predeterminada
            const option = options[i];
            const text = option.text.toLowerCase();

            option.style.display = text.includes(input) ? 'block' : 'none'; // Muestra u oculta opciones
        }

        // Seleccionar el primer producto que coincida si hay resultados
        for (let i = 1; i < options.length; i++) {
            if (options[i].style.display === 'block') {
                select.selectedIndex = i; // Selecciona la primera opción visible
                return;
            }
        }

        // Si no hay coincidencias, limpia el select
        select.selectedIndex = 0;
    };

    // Función para agregar productos a la tabla
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.text;
        const productPrice = parseFloat(selectedOption.getAttribute('data-price'));
        const productQuantity = selectedOption.getAttribute('data-quantity');

        if (!productId) return;

        // Verificar si el producto ya está en la tabla
        const existingRow = Array.from(productsTableBody.rows).find(row => row.dataset.productId === productId);
        if (existingRow) {
            const quantityInput = existingRow.querySelector('input[name*="[quantity]"]');
            const newQuantity = parseInt(quantityInput.value) + 1;

            if (newQuantity <= productQuantity) {
                quantityInput.value = newQuantity;
                updateSubtotal(existingRow); // Actualizar subtotal
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
            <td>${productPrice.toFixed(2)} Bs</td>
            <td class="subtotal">0.00 Bs</td> <!-- Columna de subtotal -->
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </td>
        `;

        productsTableBody.appendChild(row);
        updateSubtotal(row); // Calcular subtotal al agregar producto

        // Limpiar selección
        productSelect.selectedIndex = 0;
    });

    // Función para actualizar el subtotal
    function updateSubtotal(row) {
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const price = parseFloat(row.cells[2].textContent.replace(' Bs', ''));
        const quantity = parseInt(quantityInput.value);
        const subtotal = price * quantity;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2) + ' Bs'; // Actualizar columna de subtotal
    }

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

    // Actualizar el subtotal cuando cambie la cantidad
    productsTableBody.addEventListener('input', function(event) {
        if (event.target.name.includes('quantity')) {
            const row = event.target.closest('tr');
            updateSubtotal(row); // Actualiza el subtotal cuando se cambia la cantidad
        }
    });
});
</script>
@endpush
@endsection
