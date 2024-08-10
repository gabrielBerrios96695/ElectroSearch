@extends('layouts.app')

@section('breadcrumbs')
    
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                     <div class="card-body-icon">
                        <i class="fas fa-store"></i> <!-- El ícono puede ser más representativo para tiendas -->
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
                    <div class="text-success"></div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-user-tie"></i> <!-- Ícono para Vendedores -->
                    </div>
                    <div class="mr-5">Vendedores</div>
                    <div class="display-4">{{ $sellersCount }}</div>
                    <div class="text-success"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-users"></i> <!-- Ícono para Usuarios -->
                    </div>
                    <div class="mr-5">Clientes</div>
                    <div class="display-4">{{ $clientsCount }}</div>
                    <div class="text-danger"></div>
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
</div>


@endsection

