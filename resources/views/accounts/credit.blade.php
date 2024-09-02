@extends('layouts.app')

@section('breadcrumbs')
    / Cuentas / Abonar
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-dollar-sign"></i> Abonar a Cuenta</h1>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-dollar-sign"></i> Abonar a Cuenta
        </div>
        <div class="card-body">
            <form action="{{ route('account.credit', $account->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad a Abonar</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" step="0.01" min="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary">Abonar</button>
            </form>
        </div>
    </div>
</div>
@endsection
