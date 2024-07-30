@extends('layouts.app')

@section('breadcrumb')
    @php
        $breadcrumbs = [
            ['name' => 'Inicio', 'url' => route('dashboard')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
        ];
    @endphp
    @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Registrar Nuevo Usuario</a>
    </div>

    <div class="card">
        <div class="card-header">
            Usuarios
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Rol</th> <!-- Corregido a "Rol" -->
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role == 'vendedor')
                                    Vendedor
                                @elseif ($user->role == 'cliente')
                                    Cliente
                                @else
                                    {{ ucfirst($user->role) }} <!-- Mostrar el rol aquí -->
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">Editar</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
