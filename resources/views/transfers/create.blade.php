@extends('layouts.app')

@section('breadcrumbs')
    / Transferencias / Crear
@endsection

@section('content')
<div class="container">
    <h1 class="h3 text-primary"><i class="fas fa-exchange-alt"></i> Realizar Transferencia</h1>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-exchange-alt"></i> Realizar Transferencia
        </div>
        <div class="card-body">
            <form action="{{ route('transfer.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="cuenta_origen" class="form-label">Cuenta de Origen</label>
                    <select id="cuenta_origen" name="cuenta_origen" class="form-select" required>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->idCuenta }}">{{ $account->nombre }} (Saldo: ${{ number_format($account->saldo, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cuenta_destino" class="form-label">Cuenta de Destino</label>
                    <select id="cuenta_destino" name="cuenta_destino" class="form-select" required>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->idCuenta }}">{{ $account->nombre }} (Saldo: ${{ number_format($account->saldo, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Realizar Transferencia</button>
            </form>
        </div>
    </div>
</div>
@endsection
