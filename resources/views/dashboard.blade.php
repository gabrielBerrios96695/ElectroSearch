@extends('layouts.app')

@section('breadcrumbs')
    
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Cards -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="mr-5">Potential growth</div>
                    <div class="display-4">$12.34</div>
                    <div class="text-success">+3.5%</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="mr-5">Revenue current</div>
                    <div class="display-4">$17.34</div>
                    <div class="text-success">+11%</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="mr-5">Daily Income</div>
                    <div class="display-4">$12.34</div>
                    <div class="text-danger">-2.4%</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="mr-5">Expense current</div>
                    <div class="display-4">$31.53</div>
                    <div class="text-success">+3.5%</div>
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

