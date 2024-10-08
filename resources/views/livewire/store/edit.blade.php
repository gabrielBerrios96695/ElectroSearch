@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('store.index') }}" >Tiendas</a> / <a>Editar</a>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Tienda</h1>
    </div>

    <div class="card">
        <div class="card-header">
            Editar Tienda
        </div>
        <div class="card-body">
            <form action="{{ route('store.update', $store->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la tienda</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $store->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitud</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" value="{{ $store->latitude }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitud</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" value="{{ $store->longitude }}" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Tienda</button>
            </form>
            <div id="map" style="height: 500px; width: 100%; margin-top: 20px;"></div>
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
        const latitude = parseFloat('{{ $store->latitude }}');
        const longitude = parseFloat('{{ $store->longitude }}');

        const map = L.map('map').setView([latitude, longitude], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([latitude, longitude]).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            marker.setLatLng([lat, lng]);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script>
@endpush
@endsection
