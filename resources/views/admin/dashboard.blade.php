@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Painel Administrativo AMCIG</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dados</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Dados da Associação</h4>
                        
                    </div>
                    <div class="card-body">
                        <div class="row gy-6">
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-user-star-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ \App\User::where('status', 'aprovado')->count() }}">{{ \App\User::where('status', 'aprovado')->count() }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Associados Ativos</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.associados.index') }}" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-warning bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-time-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ \App\User::where('status', 'pendente')->count() }}">{{ \App\User::where('status', 'pendente')->count() }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Associados aguardando aprovação</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.associados.pendentes') }}" class="btn btn-sm btn-warning">Ver Pendentes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-success bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-home-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ \App\User::where('status', 'aprovado')->where('tipo_associado', 'morador')->count() }}">{{ \App\User::where('status', 'aprovado')->where('tipo_associado', 'morador')->count() }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Moradores Associados</p>
                                        <div class="mt-3">
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-info bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-store-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ \App\User::where('status', 'aprovado')->where('tipo_associado', 'comerciante')->count() }}">{{ \App\User::where('status', 'aprovado')->where('tipo_associado', 'comerciante')->count() }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Comerciantes Associados</p>
                                        <div class="mt-3">
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Financeira -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Resumo Financeiro</h4>
                    </div>
                    <div class="card-body">
                        <div class="row gy-6">
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-success bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ number_format(\App\Payment::whereIn('status', ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->sum('value'), 2, ',', '.') }}">R$ {{ number_format(\App\Payment::whereIn('status', ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->sum('value'), 2, ',', '.') }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Total Recebido</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.financeiro.index') }}" class="btn btn-sm btn-primary">Ver Financeiro</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-warning bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-time-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ number_format(\App\Payment::where('status', 'PENDING')->sum('value'), 2, ',', '.') }}">R$ {{ number_format(\App\Payment::where('status', 'PENDING')->sum('value'), 2, ',', '.') }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Pendente de Recebimento</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.financeiro.pagamentos', ['status' => 'PENDING']) }}" class="btn btn-sm btn-primary">Ver Pendentes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-danger bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-error-warning-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ number_format(\App\Payment::where('status', 'OVERDUE')->sum('value'), 2, ',', '.') }}">R$ {{ number_format(\App\Payment::where('status', 'OVERDUE')->sum('value'), 2, ',', '.') }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Valores Vencidos</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.financeiro.pagamentos', ['status' => 'OVERDUE']) }}" class="btn btn-sm btn-primary">Ver Vencidos</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-info bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="{{ number_format(\App\Payment::whereIn('status', ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->whereMonth('payment_date', \Carbon\Carbon::now()->month)->whereYear('payment_date', \Carbon\Carbon::now()->year)->sum('value'), 2, ',', '.') }}">R$ {{ number_format(\App\Payment::whereIn('status', ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->whereMonth('payment_date', \Carbon\Carbon::now()->month)->whereYear('payment_date', \Carbon\Carbon::now()->year)->sum('value'), 2, ',', '.') }}</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Recebido Este Mês</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.financeiro.relatorio', ['data_inicio' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'data_fim' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')]) }}" class="btn btn-sm btn-primary">Relatório do Mês</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection
