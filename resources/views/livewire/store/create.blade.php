@extends('layouts.app')
@section('breadcrumbs')
    <a href="{{ route('store.index') }}">Tiendas</a> / <a href="#">Crear</a>
@endsection
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Crear Nueva Tienda</h1>
        <a href="{{ route('store.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>

    <div class="card">
        <div class="card-header">
            Datos de la Tienda
        </div>
        <div class="card-body">
            <form action="{{ route('store.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre de la Tienda</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="latitude">Latitud</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" value="-17.4136">
                </div>

                <div class="form-group">
                    <label for="longitude">Longitud</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" value="-66.1652">
                </div>

                <div class="form-group">
                    <div id="map" style="height: 400px;"></div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Tienda</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('leaflet/leaflet.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);

        const map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('Ubicación')
            .openPopup();

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            L.marker([lat, lng]).addTo(map)
                .bindPopup('Nueva ubicación')
                .openPopup();
        });
    });
</script>
@endpush
@endsection
