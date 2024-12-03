@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Mensaje de éxito -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validación de cambio de contraseña -->
    @if (Auth::user()->id != 1 && Auth::user()->passwordUpdate)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h4>Cambio de Contraseña Requerido</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Por motivos de seguridad, es necesario actualizar su contraseña para proteger su cuenta.
                        </p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Resumen General -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-user-tie fa-3x me-3"></i>
                        <div>
                            <h4>Vendedores</h4>
                            <h2 class="display-5">{{ $sellersCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-users fa-3x me-3"></i>
                        <div>
                            <h4>Usuarios</h4>
                            <h2 class="display-5">{{ $clientsCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-3x me-3"></i>
                        <div>
                            <h4>Productos con Bajo Stock</h4>
                            <h2 class="display-5">{{ $lowStockProducts->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas Detalladas -->
        <div class="row">
            <!-- Top de Vendedores -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">Top de Vendedores</h5>
                    </div>
                    <div class="card-body">
                        @if ($topSellers->isEmpty())
                            <p class="text-muted">No hay datos de ventas este mes.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($topSellers as $seller)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>{{ $seller->seller_name }}</span>
                                        <span class="badge bg-primary">{{ number_format($seller->total_sales, 2) }} Bs.</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="m-0">Productos Más Vendidos</h5>
                    </div>
                    <div class="card-body">
                        @if ($topProducts->isEmpty())
                            <p class="text-muted">No hay datos de ventas este mes.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($topProducts as $product)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>{{ $product->product_name }}</span>
                                        <span class="badge bg-success">{{ $product->total_quantity_sold }} vendidos</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Productos con Bajo Stock -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="m-0">Productos con Bajo Stock</h5>
                    </div>
                    <div class="card-body">
                        @if ($lowStockProducts->isEmpty())
                            <p class="text-muted">No hay productos con bajo stock.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    #earningsChart {
    width: 100%;
    height: 300px;
}

</style>
@endsection
@push('scripts')
<script>
    // Obtener los datos desde PHP (usando los datos pasados por el controlador)
    var months = @json($months);
    var earnings = @json($earnings);

    // Configuración del gráfico
    var ctx = document.getElementById('earningsChart').getContext('2d');
    var earningsChart = new Chart(ctx, {
        type: 'line', // Tipo de gráfico
        data: {
            labels: months, // Meses en el eje X
            datasets: [{
                label: 'Ganancias por Mes',
                data: earnings, // Ganancias en el eje Y
                borderColor: 'rgba(75, 192, 192, 1)', // Color de la línea
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fondo de los puntos
                fill: true, // Rellenar el área bajo la línea
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Ganancias ($)'
                    }
                }
            }
        }
    });
</script>
@endpush
