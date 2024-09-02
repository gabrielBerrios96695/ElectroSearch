<!-- resources/views/accounts/index.blade.php -->
@extends('layouts.app')

@section('breadcrumbs')
    / Cuentas
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-wallet"></i> Lista de Cuentas</h1>
        <div>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Cuenta
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-wallet"></i> Cuentas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> ID</th>
                            <th scope="col"><i class="fas fa-user"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Saldo</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <th scope="row">{{ $account->idCuenta }}</th>
                                <td>{{ $account->nombre }}</td>
                                <td>${{ number_format($account->saldo, 2) }}</td>
                                <td>
                                    <a href="{{ route('accounts.edit', $account->idCuenta) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('accounts.credit', $account->idCuenta) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="input-group mt-2">
                                            <input type="number" name="cantidad" class="form-control" step="0.01" min="0" placeholder="Abonar">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i> Abonar
                                            </button>
                                        </div>
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

