@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (Auth::user()->role == 2 && Auth::user()->passwordUpdate)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Cambio de Contraseña</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Por motivos de seguridad, es necesario que cambie su contraseña. 
                            Esto garantiza que su cuenta se mantenga protegida y segura.
                        </p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Nueva Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="container">
        <h1 class="my-4">Dashboard Ecológico</h1>

        <!-- Resumen General -->
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Impacto Ambiental</h5>
                        <p class="card-text">Datos del impacto ambiental actual.</p>
                        <!-- Puedes agregar más detalles o indicadores aquí -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Reciclaje en Tiempo Real</h5>
                        <canvas id="recyclingChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Puntos de Recolección</h5>
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Estadísticas -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Cantidad de Residuos Reciclados</h5>
                        <canvas id="wasteChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Tendencia Mensual de Reciclaje</h5>
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos para los gráficos
        const recyclingCtx = document.getElementById('recyclingChart').getContext('2d');
        const wasteCtx = document.getElementById('wasteChart').getContext('2d');
        const trendCtx = document.getElementById('trendChart').getContext('2d');

        // Gráfico de barras para reciclaje en tiempo real
        new Chart(recyclingCtx, {
            type: 'bar',
            data: {
                labels: ['Papel', 'Plástico', 'Vidrio', 'Metales', 'Orgánicos'],
                datasets: [{
                    label: 'Cantidad Reciclada',
                    data: [120, 150, 180, 90, 50],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de barras para cantidad de residuos reciclados
        new Chart(wasteCtx, {
            type: 'bar',
            data: {
                labels: ['Papel', 'Plástico', 'Vidrio', 'Metales', 'Orgánicos'],
                datasets: [{
                    label: 'Cantidad Reciclada',
                    data: [300, 400, 500, 200, 100],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de líneas para la tendencia mensual
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Reciclaje Mensual',
                    data: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Inicialización del mapa
        const map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Añadir puntos de recolección (ejemplo)
        L.marker([51.5, -0.09]).addTo(map)
            .bindPopup('Punto de Recolección 1')
            .openPopup();

        L.marker([51.515, -0.1]).addTo(map)
            .bindPopup('Punto de Recolección 2');
    </script>
    @endif
</div>
@endsection
