@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('collection_points.index') }}">/ Puntos de Recolección</a> / Editar
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Punto de Recolección</h1>
        <a href="{{ route('collection_points.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i> Editar Punto de Recolección
        </div>
        <div class="card-body">
            <form action="{{ route('collection_points.update', $collectionPoint->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Punto de Recolección</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $collectionPoint->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type_point_id" class="form-label">Tipo de Punto</label>
                    <select id="type_point_id" name="type_point_id" class="form-select" required>
                        @foreach($typePoints as $typePoint)
                            <option value="{{ $typePoint->id }}" {{ $collectionPoint->type_point_id == $typePoint->id ? 'selected' : '' }}>
                                {{ $typePoint->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_point_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div id="map" style="height: 400px; margin-top: 20px;"></div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="latitude" class="form-label">Latitud</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', $collectionPoint->latitude) }}" readonly>
                        @error('latitude')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="longitude" class="form-label">Longitud</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', $collectionPoint->longitude) }}" readonly>
                        @error('longitude')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="opening_time" class="form-label">Hora de Apertura</label>
                        <input type="time" id="opening_time" name="opening_time" class="form-control" value="{{ old('opening_time', $collectionPoint->opening_time) }}" required>
                        @error('opening_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class=col-md-6">
                        <label for="closing_time" class="form-label">Hora de Cierre</label>
                        <input type="time" id="closing_time" name="closing_time" class="form-control" value="{{ old('closing_time', $collectionPoint->closing_time) }}" required>
                        @error('closing_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
               

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Punto de Recolección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    .form-label {
        color: #000;
    }
    .text-danger {
        font-size: 0.875em;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latitude = parseFloat('{{ $collectionPoint->latitude }}');
        const longitude = parseFloat('{{ $collectionPoint->longitude }}');

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
