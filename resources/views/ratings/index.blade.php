@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">Calificaciones y Comentarios</h1>
@endsection

@section('content')
<div class="container">
    <h2>Lista de Calificaciones y Comentarios</h2>

    <!-- Mostrar el botón para crear calificaciones solo si el usuario NO es administrador (role != 1) -->
    @if(auth()->user()->role != 1)
        <a href="{{ route('ratings.create') }}" class="btn btn-primary mb-3">
            Crear Calificación
        </a>
    @endif

    <!-- Mostrar el botón para aprobar las calificaciones solo si el usuario es administrador (role == 1) -->
    @if(auth()->user()->role == 1)
        <a href="{{ route('ratings.approve') }}" class="btn btn-success mb-3">
            Aprobar Calificaciones
        </a>
    @endif

    @if($productsWithAvgRatings->isEmpty())
        <p>No hay calificaciones disponibles.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Promedio de Calificación</th>
                        <th>Cantidad de Calificaciones</th>
                        <th>Ver Calificaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productsWithAvgRatings as $productData)
                        <tr>
                            <td>{{ $productData['product']->name }}</td>
                            <td>
                                <!-- Mostrar imagen del producto si existe -->
                                @if(!empty($productData['product']->image))
                                    <img src="{{ asset('storage/images/' . $productData['product']->image) }}" 
                                         alt="Imagen de {{ $productData['product']->name }}" 
                                         class="product-image" 
                                         style="max-width: 150px; max-height: 120px;">
                                @else
                                    <span>No disponible</span>
                                @endif
                            </td>
                            <td>
                                <!-- Mostrar calificación promedio con estrellas -->
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $productData['averageRating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span> {{ $productData['averageRating'] }}</span> <!-- Promedio numérico -->
                            </td>
                            <td>
                                <span>{{ $productData['ratingsCount'] }} calificaciones</span>
                            </td>
                            <td>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ratingModal-{{ $productData['product']->id }}">
                                    Ver Calificaciones
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para mostrar calificaciones de cada producto -->
                        <div class="modal fade" id="ratingModal-{{ $productData['product']->id }}" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ratingModalLabel">Calificaciones de {{ $productData['product']->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($productData['ratings']->isEmpty())
                                            <p>No hay calificaciones disponibles para este producto.</p>
                                        @else
                                            @foreach($productData['ratings'] as $rating)
                                                @if($rating->comment_status == 3) <!-- Solo mostrar comentarios aprobados -->
                                                    <div class="mb-4">
                                                        <strong>{{ $rating->user->name }}</strong> <!-- Nombre del usuario -->
                                                        <div>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <p>{{ $rating->comment }}</p> <!-- Comentario -->
                                                        <hr> <!-- Línea de separación -->
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
