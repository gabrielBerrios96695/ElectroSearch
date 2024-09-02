<!-- resources/views/accounts/edit.blade.php -->
@extends('layouts.app')

@section('breadcrumbs')
    / Editar Cuenta
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-wallet"></i> Editar Cuenta</h1>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-wallet"></i> Editar Cuenta
        </div>
        <div class="card-body">
            <form action="{{ route('accounts.update', $account->idCuenta) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $account->nombre) }}" required>
                </div>
                <div class="mb-3">
                    <label for="saldo" class="form-label">Saldo</label>
                    <input type="number" id="saldo" name="saldo" class="form-control" value="{{ old('saldo', $account->saldo) }}" step="0.01" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Cuenta</button>
            </form>
        </div>
    </div>
</div>
@endsection
