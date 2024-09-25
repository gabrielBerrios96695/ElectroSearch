@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-center mb-6">Mapa de Tiendas</h1>
    <h2 class="text-lg text-center mb-4">Total de Tiendas: {{ $stores->count() }}</h2>

    <div id="map" style="height: 500px;"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([0, 0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var group = L.featureGroup();

            @foreach ($stores as $store)
                var markerColor = "{{ $store->status == 1 ? 'blue' : 'red' }}";
                var marker = L.circleMarker([{{ $store->latitude }}, {{ $store->longitude }}], {
                    color: markerColor,
                    fillColor: markerColor,
                    fillOpacity: 0.6,
                    radius: 8
                }).addTo(map);

                marker.bindPopup('<b>{{ $store->name }}</b><br>Ubicación: [{{ $store->latitude }}, {{ $store->longitude }}]');

                group.addLayer(marker);
            @endforeach

            if (group.getLayers().length > 0) {
                map.fitBounds(group.getBounds());
            } else {
                map.setView([0, 0], 2); // Si no hay tiendas, vuelve a la vista inicial
            }
        });

        function verUbicacion(latitude, longitude) {
            L.popup()
                .setLatLng([latitude, longitude])
                .setContent('Ubicación de la tienda')
                .openOn(map);
        }
    </script>

    <style>
        #map {
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</div>
@endsection
