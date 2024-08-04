@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('store.index') }}">/ Tiendas</a> / Crear
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Crear Nueva Tienda</h1>
        <a href="{{ route('store.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-store"></i> Datos de la Tienda
        </div>
        <div class="card-body">
            <form action="{{ route('store.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nombre de la Tienda</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div id="map" style="height: 300px; width: 100%; margin-top: 20px;"></div>
                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitud</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" value="-17.4136" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitud</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" value="-66.1652" readonly>
                    </div>
                </div>

                

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Tienda
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
<!-- Include Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const initialLat = parseFloat(document.getElementById('latitude').value);
        const initialLng = parseFloat(document.getElementById('longitude').value);

        const map = L.map('map').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const icon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let marker = L.marker([initialLat, initialLng], { icon: icon }).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng], { icon: icon }).addTo(map);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script>
@endpush
@endsection
