@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Editar Empleado</h1>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
