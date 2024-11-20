@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Venta</h1>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        Crear Usuario
    </button>
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createUserModalLabel">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="createUserForm" action="{{ route('sales.createUser') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="second_last_name" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="second_last_name" name="second_last_name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-success">Crear Usuario</button>
                    </form>
      </div>
    </div>
  </div>
</div>
    <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
        @csrf

        <!-- Selección del cliente -->
        <div class="mb-3 row">
            <label for="customer_name" class="form-label">Cliente</label>
            <div class="col-md-8">
                <input list="customer-list" id="customer_name" class="form-control" placeholder="Selecciona un cliente" required>
                <datalist id="customer-list">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }} {{ $customer->last_name }} {{ $customer->second_last_name }}" data-id="{{ $customer->id }}">
                            {{ $customer->name }} {{ $customer->last_name }} {{ $customer->second_last_name }}
                        </option>
                    @endforeach
                </datalist>
                <input type="hidden" id="customer_id" name="customer_id" required>
            </div>
        </div>

        <!-- Selección del producto -->
        <div class="mb-4 row">
            <label for="product_id" class="form-label"><i class="fas fa-box"></i> Agregar Producto</label>
            <div class="col-md-8">
                <input list="product-list" id="product_name" class="form-control" placeholder="Selecciona un producto" required>
                <datalist id="product-list">
                    @foreach ($products as $product)
                        @if ($product->quantity > 0)
                            <option value="{{ $product->name }}" 
                                    data-id="{{ $product->id }}" 
                                    data-price="{{ $product->price }}" 
                                    data-quantity="{{ $product->quantity }}">
                                {{ $product->name }} -{{ $product->quantity }} - {{ $product->price }}Bs
                            </option>
                        @endif
                    @endforeach
                </datalist>
                <input type="hidden" id="product_id" name="product_id" required>
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
    const addProductBtn = document.getElementById('add-product-btn');
    const productsTableBody = document.querySelector('#products-table tbody');
    const confirmSaleBtn = document.getElementById('confirm-sale-btn');
    const saleSummary = document.getElementById('sale-summary');
    const totalSaleAmount = document.getElementById('total-sale-amount');
    const submitSaleBtn = document.getElementById('submit-sale-btn');
    const saleForm = document.getElementById('sale-form');

    // Función para agregar productos a la tabla
    addProductBtn.addEventListener('click', function() {
        const productInput = document.getElementById('product_name');
        const selectedOption = Array.from(document.getElementById('product-list').options)
            .find(option => option.value === productInput.value);
        if (!selectedOption) return;

        const productId = selectedOption.getAttribute('data-id');
        const productName = selectedOption.value;
        const productPrice = parseFloat(selectedOption.getAttribute('data-price'));
        const productQuantity = parseInt(selectedOption.getAttribute('data-quantity'));

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
        const price = parseFloat(row.cells[3].textContent.replace(' Bs', ''));
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
            const productName = row.cells[0].textContent;
            const quantity = row.querySelector('input[name*="[quantity]"]').value;
            const price = parseFloat(row.cells[3].textContent.replace(' Bs', ''));
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

    // Capturar el ID del cliente seleccionado
    document.getElementById('customer_name').addEventListener('input', function() {
        const selectedOption = Array.from(document.getElementById('customer-list').options)
            .find(option => option.value === this.value);
        if (selectedOption) {
            document.getElementById('customer_id').value = selectedOption.getAttribute('data-id');
        }
    });
    document.getElementById('createUserForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    if (password !== passwordConfirmation) {
      event.preventDefault(); // Evita que el formulario se envíe
      alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
    }
  });
});
</script>
@endpush
@endsection
