@extends('layouts.app')

@section('breadcrumbs')
    / Transferencias / Abonar Cuenta
@endsection

@section('content')
<div class="container">
    <h1 class="h3 text-primary"><i class="fas fa-money-bill-wave"></i> Abonar Cuenta</h1>

    <form action="{{ route('transfers.credit') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header card-header-custom">
                Información de Abono
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="cuenta_id">Cuenta</label>
                    <select name="cuenta_id" id="cuenta_id" class="form-control">
                        <option value="">Seleccionar Cuenta</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->idCuenta }}">{{ $cuenta->name }}</option>
                        @endforeach
                    </select>
                    @error('cuenta_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" step="0.01" name="cantidad" id="cantidad" class="form-control">
                    @error('cantidad')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Abonar</button>
            </div>
        </div>
    </form>
</div>
@endsection
