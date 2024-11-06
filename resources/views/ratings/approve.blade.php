@extends('layouts.app')

@section('breadcrumbs')
    <h1 class="text-white">Calificaciones por Aprobar o Bloquear</h1>
@endsection

@section('content')
<div class="container">
    <h2>Lista de Calificaciones Pendientes o Bloqueadas</h2>

    @if($ratings->isEmpty())
        <p>No hay calificaciones pendientes de aprobación o bloqueadas.</p>
    @else
        <div class="card">
            <div class="card-header">
                <i class="fas fa-star"></i> Calificaciones Pendientes o Bloqueadas
            </div>
            <div class="card-body">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Vendedor</th>
                            <th scope="col">Calificación</th>
                            <th scope="col">Comentario</th>
                            <th scope="col">Estado del Comentario</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ratings as $rating)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td>{{ $rating->saleDetail->product->name }}</td>
                                <td>{{ $rating->user->name }}</td>
                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td>
                                    <!-- Mostrar el comentario completo -->
                                    <div class="comment-text">
                                        {{ $rating->comment }}
                                    </div>
                                </td>
                                <td>
                                    @if($rating->comment_status == 1) <!-- Pendiente -->
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($rating->comment_status == 2) <!-- Bloqueado -->
                                        <span class="badge bg-danger">Bloqueado</span>
                                    @elseif($rating->comment_status == 3) <!-- Aprobado -->
                                        <span class="badge bg-success">Aprobado</span>
                                    @endif
                                </td>
                                <td>
                                  
                                
                                        <a href="{{ route('ratings.approve.comment', $rating->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Aprobar
                                        </a>
                                        <a href="{{ route('ratings.block.comment', $rating->id) }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-ban"></i> Bloquear
                                        </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
