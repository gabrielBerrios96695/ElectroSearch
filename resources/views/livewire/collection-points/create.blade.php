@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('collection_points.index') }}">/ Puntos de Recolección</a> / Crear
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Crear Nuevo Punto de Recolección</h1>
        <a href="{{ route('collection_points.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="card shadow-custom border-custom">
        <div class="card-header card-header-custom">
            <i class="fas fa-trash"></i> Datos del Punto de Recolección
        </div>
        <div class="card-body">
            <form action="{{ route('collection_points.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nombre del Punto de Recolección</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="type_point_id" class="form-label">Tipo de Punto</label>
                    <select id="type_point_id" name="type_point_id" class="form-control" required>
                        <option value="">Seleccione un Tipo de Punto</option>
                        @foreach ($typePoints as $typePoint)
                            <option value="{{ $typePoint->id }}" {{ old('type_point_id') == $typePoint->id ? 'selected' : '' }}>
                                {{ $typePoint->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_point_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div id="map" style="height: 300px; width: 100%; margin-top: 20px;"></div>

                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitud</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', '-17.4136') }}" readonly>
                        @error('latitude')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitud</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', '-66.1652') }}" readonly>
                        @error('longitude')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="opening_time" class="form-label">Hora de Apertura</label>
                    <input type="time" id="opening_time" name="opening_time" class="form-control" value="{{ old('opening_time') }}" required>
                    @error('opening_time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="closing_time" class="form-label">Hora de Cierre</label>
                    <input type="time" id="closing_time" name="closing_time" class="form-control" value="{{ old('closing_time') }}" required>
                    @error('closing_time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Punto de Recolección
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
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
