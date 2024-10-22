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
            <div class="col-md-4 mt-2">
                <!-- Botón para abrir el modal de nuevo usuario -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    Crear Nuevo Usuario
                </button>
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

<!-- Modal de confirmación de venta -->
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

<!-- Modal para crear un nuevo usuario con rol 3 -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sales.createUser') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <input type="text" class="form-control" id="second_last_name" name="second_last_name" value="nilo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <input type="hidden" name="role" value="3">
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
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

        for (let i = 1; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            option.style.display = text.includes(input) ? 'block' : 'none';
        }

        for (let i = 1; i < options.length; i++) {
            if (options[i].style.display === 'block') {
                select.selectedIndex = i;
                return;
            }
        }

        select.selectedIndex = 0;
    };

    // Función para filtrar productos
    window.filterProducts = function() {
        const input = document.getElementById('product_filter').value.toLowerCase();
        const select = document.getElementById('product-select');
        const options = select.options;

        for (let i = 1; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            option.style.display = text.includes(input) ? 'block' : 'none';
        }

        for (let i = 1; i < options.length; i++) {
            if (options[i].style.display === 'block') {
                select.selectedIndex = i;
                return;
            }
        }

        select.selectedIndex = 0;
    };

    // Agregar producto a la tabla
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.text;
        const productPrice = selectedOption.getAttribute('data-price');
        const productQuantity = selectedOption.getAttribute('data-quantity');

        const newRow = `
            <tr>
                <td>${productName}</td>
                <td><input type="number" class="form-control" name="products[${productId}][quantity]" value="1" min="1" max="${productQuantity}" required></td>
                <td>${productPrice}</td>
                <td>${productPrice}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product-btn">Eliminar</button></td>
            </tr>
        `;

        productsTableBody.insertAdjacentHTML('beforeend', newRow);
    });

    // Confirmar venta
    confirmSaleBtn.addEventListener('click', function() {
        const rows = productsTableBody.querySelectorAll('tr');
        let total = 0;
        saleSummary.innerHTML = '';

        rows.forEach(function(row) {
            const productName = row.children[0].textContent;
            const quantity = row.children[1].children[0].value;
            const price = row.children[2].textContent;
            const subtotal = quantity * price;
            total += subtotal;

            saleSummary.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${productName}</td>
                    <td>${quantity}</td>
                    <td>${price}</td>
                    <td>${subtotal}</td>
                </tr>
            `);
        });

        totalSaleAmount.textContent = total;
        $('#confirmSaleModal').modal('show');
    });

    // Enviar formulario de venta al confirmar en el modal
    submitSaleBtn.addEventListener('click', function() {
        saleForm.submit();
    });

    // Eliminar producto de la tabla
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-product-btn')) {
            event.target.closest('tr').remove();
        }
    });
});
</script>
@endpush
@endsection
