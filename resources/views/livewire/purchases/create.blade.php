@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Venta</h1>

    <form id="sale-form" action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <div class="mb-4 row">

            <div class="col-md-6">
                <label for="product_name" class="form-label"><i class="fas fa-box"></i> Agregar Producto</label>
                <input list="product-list" id="product_name" class="form-control" placeholder="Selecciona un producto" required>
                <datalist id="product-list">

                </datalist>
                <input type="hidden" id="product_id" name="product_id" required>
            </div>

            <div class="col-md-6">
                <label for="category_id" class="form-label"><i class="fas fa-filter"></i> Filtrar por Categoría</label>
                <select id="category_id" class="form-control">
                    <option value="">Seleccionar categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
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
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Adquirir</th>
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
    const categorySelect = document.getElementById('category_id');
    const addProductBtn = document.getElementById('add-product-btn');
    const productsTableBody = document.querySelector('#products-table tbody');
    const confirmSaleBtn = document.getElementById('confirm-sale-btn');
    const saleSummary = document.getElementById('sale-summary');
    const totalSaleAmount = document.getElementById('total-sale-amount');
    const submitSaleBtn = document.getElementById('submit-sale-btn');
    const saleForm = document.getElementById('sale-form');
    const productInput = document.getElementById('product_name');
    const productList = document.getElementById('product-list');

    // Función para filtrar productos por categoría
    categorySelect.addEventListener('change', function() {
        const categoryId = categorySelect.value;
        filterProductsByCategory(categoryId);
    });

    // Función para cargar los productos filtrados por categoría
    function filterProductsByCategory(categoryId) {
        const options = [...productList.options];
        options.forEach(option => option.remove()); // Limpiar la lista de opciones
        @foreach ($products as $product)
            if (!categoryId || {{ $product->category_id }} == categoryId) {
                const option = document.createElement('option');
                option.value = '{{ $product->name }}';
                option.setAttribute('data-id', '{{ $product->id }}');
                option.setAttribute('data-price', '{{ $product->price }}');
                option.setAttribute('data-quantity', '{{ $product->quantity }}');
                option.setAttribute('data-image', '{{ $product->image }}');
                productList.appendChild(option);
            }
        @endforeach
    }

    // Función para agregar productos a la tabla
    addProductBtn.addEventListener('click', function() {
        const selectedOption = Array.from(productList.options).find(option => option.value === productInput.value);
        if (!selectedOption) return;

        const productId = selectedOption.getAttribute('data-id');
        const productName = selectedOption.value;
        const productPrice = parseFloat(selectedOption.getAttribute('data-price'));
        const productQuantity = parseInt(selectedOption.getAttribute('data-quantity'));
        const productImage = selectedOption.getAttribute('data-image');

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
            <td>
                <img src="/storage/images/${productImage}" 
                     alt="${productName}" 
                     class="product-image img-thumbnail" 
                     style="max-width: 150px; max-height: 120px;">
            </td>
            <td>${productName}</td>
            <td>${productQuantity}</td>
            <td>
                <input type="number" name="products[${productsTableBody.rows.length}][quantity]" 
                       value="1" min="1" max="${productQuantity}" 
                       class="form-control" required>
                <input type="hidden" name="products[${productsTableBody.rows.length}][id]" value="${productId}">
            </td>
            <td>${productPrice.toFixed(2)} Bs</td>
            <td class="subtotal">0.00 Bs</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </td>
        `;
        productsTableBody.appendChild(row);
        updateSubtotal(row); // Calcular subtotal al agregar producto

        // Limpiar selección
        productInput.value = '';
    });

    // Función para actualizar el subtotal
    function updateSubtotal(row) {
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const price = parseFloat(row.cells[4].textContent.replace(' Bs', ''));
        const quantity = parseInt(quantityInput.value);
        const subtotal = price * quantity;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2) + ' Bs';
    }

    // Eliminar productos de la tabla
    productsTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-product-btn')) {
            event.target.closest('tr').remove();
        }
    });

    // Mostrar modal de confirmación con el resumen de la venta
    confirmSaleBtn.addEventListener('click', function() {
        saleSummary.innerHTML = ''; // Limpiar el contenido anterior del resumen
        let total = 0;

        // Recorrer filas de la tabla de productos y llenar el resumen
        Array.from(productsTableBody.rows).forEach(row => {
            const productName = row.cells[1].textContent;
            const quantity = row.querySelector('input[name*="[quantity]"]').value;
            const price = parseFloat(row.cells[4].textContent.replace(' Bs', ''));
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
        totalSaleAmount.textContent = total.toFixed(2) + ' Bs';

        // Abrir el modal si hay productos en la lista
        if (productsTableBody.rows.length > 0) {
            const confirmSaleModal = new bootstrap.Modal(document.getElementById('confirmSaleModal'));
            confirmSaleModal.show();
        } else {
            alert('Debes agregar al menos un producto para confirmar la venta.');
        }
    });

    // Confirmar la venta y enviar el formulario
    submitSaleBtn.addEventListener('click', function() {
        saleForm.submit();
    });

    // Actualizar el subtotal cuando cambie la cantidad
    productsTableBody.addEventListener('input', function(event) {
        if (event.target.matches('input[name*="[quantity]"]')) {
            const row = event.target.closest('tr');
            updateSubtotal(row);
        }
    });
});
</script>
@endpush
@endsection
