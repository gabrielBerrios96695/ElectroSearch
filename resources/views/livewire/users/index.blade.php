@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Registrar Nuevo Usuario</a>
    </div>

    <div class="card">
        <div class="card-header">
            Lista de Usuarios
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('users.index', ['sort_field' => 'id', 'sort_direction' => $sortField === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    #
                                    @if ($sortField === 'id')
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('users.index', ['sort_field' => 'name', 'sort_direction' => $sortField === 'name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    Nombre
                                    @if ($sortField === 'name')
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('users.index', ['sort_field' => 'email', 'sort_direction' => $sortField === 'email' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    Email
                                    @if ($sortField === 'email')
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
