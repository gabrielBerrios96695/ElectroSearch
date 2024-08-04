@extends('layouts.app')

@section('breadcrumbs')
    / Usuarios
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-users"></i> Usuarios
        </div>
        <div class="card-body">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Rol</th>
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
                                    {{ ucfirst($user->role) }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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
