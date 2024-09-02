@extends('layouts.app')

@section('breadcrumbs')
    / Transferencias
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-exchange-alt"></i> Lista de Transferencias</h1>
        <div>
            <a href="{{ route('transfers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Realizar Transferencia
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-exchange-alt"></i> Transferencias
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> ID</th>
                            <th scope="col"><i class="fas fa-calendar-day"></i> Fecha</th>
                            <th scope="col"><i class="fas fa-wallet"></i> Cuenta Origen</th>
                            <th scope="col"><i class="fas fa-wallet"></i> Cuenta Destino</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfers as $transfer)
                            <tr>
                                <th scope="row">{{ $transfer->idTransferencia }}</th>
                              
                                <td>{{ $transfer->cuentaOrigen->nombre }} (Saldo: ${{ number_format($transfer->cuentaOrigen->saldo, 2) }})</td>
                                <td>{{ $transfer->cuentaDestino->nombre }} (Saldo: ${{ number_format($transfer->cuentaDestino->saldo, 2) }})</td>
                                <td>${{ number_format($transfer->cantidad, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
