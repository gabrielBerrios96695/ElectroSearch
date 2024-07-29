@extends('layouts.app')
@section('breadcrumb')
    @php
        $breadcrumbs = [
            ['name' => 'Inicio', 'url' => route('dashboard')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
            ['name' => 'Editar', 'url' => '#'],
        ];
    @endphp
    @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
    </div>
   <div class="card">
        <div class="card-header">
            Formulario de Edición de Usuario
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST"">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>

                
                <button type="submit" class="btn btn-success">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
