@extends('layouts.app')

@section('content')
<div class="container-fluid">
@if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (Auth::user()->role == 2 && Auth::user()->passwordUpdate)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Cambio de Contraseña</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Por motivos de seguridad, es necesario que cambie su contraseña. 
                            Esto garantiza que su cuenta se mantenga protegida y segura.
                        </p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Nueva Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="mr-5">Tiendas Registradas</div>
                        <div class="display-4">{{ $storeCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-store-slash"></i>
                        </div>
                        <div class="mr-5">Tiendas Deshabilitadas</div>
                        <div class="display-4">{{ $storeCount1 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="mr-5">Vendedores</div>
                        <div class="display-4">{{ $sellersCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="mr-5">Clientes</div>
                        <div class="display-4">{{ $clientsCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History and Open Projects -->
        <div class="row">
            <div class="col-xl-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-header">
                        Transaction History
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="transactionHistoryChart"></canvas>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-dark text-white">Transfer to Paypal - $236</li>
                            <li class="list-group-item bg-dark text-white">Transfer to Stripe - $593</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-3">
                <div class="card bg-dark text-white h-100">
                    <div class="card-header">
                        Open Projects
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-dark text-white">
                                <i class="fas fa-folder-open text-primary"></i> Admin dashboard design
                                <span class="badge badge-primary float-right">30 tasks, 5 issues</span>
                            </li>
                            <li class="list-group-item bg-dark text-white">
                                <i class="fas fa-folder-open text-success"></i> Wordpress Development
                                <span class="badge badge-success float-right">23 tasks, 5 issues</span>
                            </li>
                            <li class="list-group-item bg-dark text-white">
                                <i class="fas fa-folder-open text-warning"></i> Project meeting
                                <span class="badge badge-warning float-right">15 tasks, 2 issues</span>
                            </li>
                            <li class="list-group-item bg-dark text-white">
                                <i class="fas fa-folder-open text-danger"></i> Broadcast Mail
                                <span class="badge badge-danger float-right">35 tasks, 7 issues</span>
                            </li>
                            <li class="list-group-item bg-dark text-white">
                                <i class="fas fa-folder-open text-info"></i> UI Design
                                <span class="badge badge-info float-right">27 tasks, 4 issues</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
