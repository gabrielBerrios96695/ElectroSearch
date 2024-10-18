@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Reporte de Vendedores que Más Dinero Acumularon en Ventas Completadas</h2>

    <!-- Filtros de fecha -->
    <form id="filter-form" method="GET" action="{{ route('reports.top_sellers') }}">
        <div class="row mb-3">
            <div class="col-md-5">
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
            <div class="col-md-5">
                <label for="end_date" class="form-label">Fecha de Fin (opcional)</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    class="form-control" 
                    value="{{ request('end_date', $endDate ?? '') }}"
                >
            </div>
            <div class="col-md-2">
                <label for="limit" class="form-label">Límite de Registros</label>
                <input 
                    type="number" 
                    id="limit" 
                    name="limit" 
                    class="form-control" 
                    min="1" 
                    max="{{ $totalSellers }}" 
                    value="{{ request('limit', 5) }}"
                >
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Aplicar</button>
                <a href="{{ route('reports.exportExcelTopSellers', request()->query()) }}" class="btn btn-warning ms-2">Descargar Excel</a>
            </div>
        </div>
    </form>

    @if($topSellers->isEmpty())
        <div class="alert alert-info">
            No se encontraron resultados para los filtros aplicados.
        </div>
    @else
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Vendedor</th>
                <th>Total Acumulado (Ventas Completadas)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSellers as $seller)
            <tr>
                <td>{{ $seller->name }}</td>
                <td>{{ number_format($seller->total_sales, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="chart-container" class="mt-4" style="max-width: 400px; margin: auto;">
        <canvas id="sellersChart"></canvas>
    </div>

    <h5 class="mt-4">Total Acumulado: {{ number_format($topSellers->sum('total_sales'), 2) }} Bs</h5>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('sellersChart').getContext('2d');
        const sellersData = @json($topSellers); // Pasar datos de PHP a JavaScript

        const labels = sellersData.map(seller => seller.name);
        const totals = sellersData.map(seller => seller.total_sales);

        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Acumulado por Vendedor',
                    data: totals,
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
                                const percentage = ((total / sellersData.reduce((a, b) => a + b.total_sales, 0)) * 100).toFixed(2);
                                return `${tooltipItem.label}: ${total} Bs (${percentage}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Total Acumulado por Vendedor'
                    }
                }
            }
        });
    </script>
    @endif
</div>
@endsection
