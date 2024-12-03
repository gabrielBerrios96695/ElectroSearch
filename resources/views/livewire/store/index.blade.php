@extends('layouts.app')

@section('breadcrumbs')
    / Tiendas
@endsection

@section('content')
@php
    use App\Models\Store;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1 class="h3 text-primary"><i class="fas fa-store-alt"></i> Lista de Tiendas</h1>

        <!-- Botón para abrir el modal de creación de tienda -->
    @if (auth()->user()->role == 1) <!-- Solo mostrar los botones si el usuario es Administrador -->
        <div>
            <a href="{{ route('store.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Registrar Nueva Tienda
    </a>

        </div>
    @endif

    </div>

    <div class="card shadow-custom border-custom">
        <div class="card-header card-header-custom">
            <i class="fas fa-store-alt"></i> Tiendas Registradas
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('store.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar tiendas..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead class="custom-bg-tertiary">
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-store"></i> Nombre de la Tienda</th>
                            <th scope="col"><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Estado</th>
                            @if (auth()->user()->role == 1) <!-- Solo mostrar la columna de Acciones si es Administrador -->
                                <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $store)
                            <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->longitude }} {{ $store->longitude }}</td>
                                <td>
                                    <span class="badge {{ $store->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $store->status == 1 ? 'Habilitada' : 'Deshabilitada' }}
                                    </span>
                                </td>

                                @if (auth()->user()->role == 1) <!-- Solo mostrar las acciones si el usuario es Administrador -->
                                    <td>
                                        <a href="{{ route('store.edit', $store->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button" class="btn {{ $store->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-store-id="{{ $store->id }}" data-store-name="{{ $store->name }}" data-store-status="{{ $store->status }}">
                                            <i class="fas {{ $store->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                        </button>
                                       <!-- Tu botón para abrir el modal -->
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#mapModal" data-lat="{{ $store->latitude }}" data-lng="{{ $store->longitude }}" data-name="{{ $store->name }}">
    Ver Ubicación
</button>

<!-- Modal para mostrar el mapa -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Ubicación de la Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Mapa de Leaflet -->
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar el mapa con las coordenadas de la tienda -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cuando se muestra el modal, inicializar el mapa
        $('#mapModal').on('shown.bs.modal', function (event) {
            // Obtener las coordenadas de la tienda desde los atributos del botón
            var button = $(event.relatedTarget);
            var lat = button.data('lat'); // Latitud
            var lng = button.data('lng'); // Longitud
            var storeName = button.data('name'); // Nombre de la tienda

            // Inicializar el mapa centrado en la ubicación de la tienda
            var map = L.map('map').setView([lat, lng], 15); // Nivel de zoom 15 es adecuado para una tienda

            // Cargar las capas de los tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Agregar un marcador en la ubicación de la tienda
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>' + storeName + '</b><br>Ubicación de la tienda')
                .openPopup();

            // Asegurarse de que el mapa se redimensione adecuadamente cuando se abre el modal
            map.invalidateSize();
        });
    });
</script>
@endpush


                                    </td>
                                    
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->role == 1) <!-- Solo mostrar los modales si el usuario es Administrador -->
<div class="modal fade" id="createStoreModal" tabindex="-1" aria-labelledby="createStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStoreModalLabel">Registrar Nueva Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear una nueva tienda -->
                <form method="POST" action="{{ route('store.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la Tienda</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Habilitada</option>
                            <option value="0">Deshabilitada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Tienda</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal de Cambio de Estado -->
    <div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleStatusModalLabel">Cambiar Estado de la Tienda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="toggleStatusForm" action="{{ route('store.toggleStatus', ['store' => 'store_id']) }}">
                        @csrf
                        @method('PATCH')
                        
                        <p id="toggleStatusMessage">¿Estás seguro de que deseas cambiar el estado de esta tienda?</p>
                        
                        <!-- Hidden inputs to store store info -->
                        <input type="hidden" name="store_id" id="toggleStatusStoreId">
                        <input type="hidden" name="status" id="toggleStatusValue">

                        <button type="submit" class="btn btn-primary" id="toggleStatusSubmit">Cambiar Estado</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para mostrar la ubicación -->
<div class="modal fade" id="storeLocationModal" tabindex="-1" aria-labelledby="storeLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="storeLocationModalLabel">Ubicación de la Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px;"></div> <!-- Contenedor para el mapa -->
            </div>
        </div>
    </div>
</div>

@endif

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#toggleStatusModal"]');
        
        statusButtons.forEach(button => {
            button.addEventListener('click', function () {
                const storeId = button.getAttribute('data-store-id');
                const storeName = button.getAttribute('data-store-name');
                const storeStatus = button.getAttribute('data-store-status');

                // Cambiar el texto del mensaje según el estado de la tienda
                const statusMessage = document.getElementById('toggleStatusMessage');
                const submitButton = document.getElementById('toggleStatusSubmit');
                const statusValueField = document.getElementById('toggleStatusValue');
                const storeIdField = document.getElementById('toggleStatusStoreId');

                // Actualizar la URL del formulario para incluir el storeId
                const formAction = "{{ route('store.toggleStatus', ['store' => 'store_id']) }}".replace('store_id', storeId);
                document.getElementById('toggleStatusForm').action = formAction;

                storeIdField.value = storeId;
                
                if (storeStatus == 1) { // Si la tienda está habilitada
                    statusMessage.textContent = `¿Estás seguro de que deseas deshabilitar a ${storeName}?`;
                    statusValueField.value = 0; // Deshabilitar
                    submitButton.textContent = 'Deshabilitar Tienda';
                } else { // Si la tienda está deshabilitada
                    statusMessage.textContent = `¿Estás seguro de que deseas habilitar a ${storeName}?`;
                    statusValueField.value = 1; // Habilitar
                    submitButton.textContent = 'Habilitar Tienda';
                }
            });
        });
        $('#storeLocationModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget); // Obtén el botón que abrió el modal
        var latitude = button.data('store-latitude'); // Obtener la latitud
        var longitude = button.data('store-longitude'); // Obtener la longitud

        // Inicializa el mapa
        var map = L.map('map').setView([latitude, longitude], 13); // Centrar en la tienda

        // Cargar el tile layer (mapa base)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Coloca el marcador en la ubicación de la tienda
        L.marker([latitude, longitude]).addTo(map)
            .bindPopup('<b>Ubicación de la tienda</b>')
            .openPopup();
    });
    });
</script>
@endpush

@endsection
