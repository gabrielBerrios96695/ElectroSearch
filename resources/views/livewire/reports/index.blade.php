@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Reporte de Productos Más Vendidos</h1>

    <form id="filter-form" method="GET" action="{{ route('reports.index') }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Fecha de Inicio</label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    class="form-control" 
                    value="{{ request('start_date', $startDate ?? now()->startOfMonth()->toDateString()) }}" 
                    required
                >
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Fecha de Fin (opcional)</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    class="form-control" 
                    value="{{ request('end_date', $endDate ?? '') }}"
                >
            </div>
            <div class="col-md-4">
                <label for="limit" class="form-label">Cantidad de Productos a Mostrar</label>
                <input 
                    type="number" 
                    id="limit" 
                    name="limit" 
                    class="form-control" 
                    min="1" 
                    max="{{ $totalProducts }}" 
                    value="{{ request('limit', 5) }}"
                >
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Aplicar</button>
                <a href="{{ route('reports.exportExcel', request()->query()) }}" class="btn btn-warning ms-2">Descargar Excel</a>
            </div>
        </div>
    </form>

    @if(isset($salesData) && count($salesData) > 0)
        <h2 class="mt-4">Resultados del Reporte</h2>
        
        {{-- Encabezado del reporte --}}
        <h4>
            @if(isset($startDate) && isset($endDate))
                Reporte de las fechas {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @elseif(isset($startDate))
                Fecha de reporte de {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
            @else
                Fecha de reporte de...
            @endif
        </h4>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Total Recaudado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesData as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ number_format($product->total, 2) }} Bs</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="chart-container" class="mt-4" style="max-width: 400px; margin: auto;">
            <canvas id="salesChart"></canvas>
        </div>

        <h5 class="mt-4">Total Acumulado: {{ number_format($salesData->sum('total'), 2) }} Bs</h5>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesData); // Pasar datos de PHP a JavaScript

            const labels = salesData.map(product => product.name);
            const quantities = salesData.map(product => product.quantity);
            const totals = salesData.map(product => product.total);

            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad de Productos Vendidos',
                        data: quantities,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const total = totals[tooltipItem.dataIndex];
                                    const quantity = quantities[tooltipItem.dataIndex];
                                    const percentage = ((quantity / salesData.reduce((a, b) => a + b.quantity, 0)) * 100).toFixed(2);
                                    return `${tooltipItem.label}: ${quantity} (${percentage}%)`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Porcentaje de Productos Vendidos'
                        }
                    }
                }
            });
        </script>
    @else
        <p>No se encontraron registros para mostrar.</p>
    @endif
</div>
@endsection
