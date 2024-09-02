@extends('layouts.app')

@section('breadcrumbs')
    / Cuentas / Crear
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-plus"></i> Crear Cuenta</h1>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-plus"></i> Crear Cuenta
        </div>
        <div class="card-body">
            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="saldo" class="form-label">Saldo Inicial</label>
                    <input type="number" id="saldo" name="saldo" class="form-control" step="0.01" min="0.00" required>
                </div>

                <button type="submit" class="btn btn-primary">Crear Cuenta</button>
            </form>
        </div>
    </div>
</div>
@endsection
