@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Controle Financeiro</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Financeiro</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Cards de Resumo Financeiro -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-success bg-light-subtle rounded-2 fs-4">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($totalRecebido, 2, ',', '.') }}">R$ {{ number_format($totalRecebido, 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Total Recebido</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.financeiro.pagamentos') }}" class="btn btn-sm btn-success">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-warning bg-light-subtle rounded-2 fs-4">
                            <i class="ri-time-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($totalPendente, 2, ',', '.') }}">R$ {{ number_format($totalPendente, 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Pendente de Recebimento</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.financeiro.pagamentos', ['status' => 'PENDING']) }}" class="btn btn-sm btn-warning">Ver Pendentes</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-danger bg-light-subtle rounded-2 fs-4">
                            <i class="ri-error-warning-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($totalVencido, 2, ',', '.') }}">R$ {{ number_format($totalVencido, 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Valores Vencidos</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.financeiro.pagamentos', ['status' => 'OVERDUE']) }}" class="btn btn-sm btn-danger">Ver Vencidos</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-info bg-light-subtle rounded-2 fs-4">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($recebimentosMesAtual, 2, ',', '.') }}">R$ {{ number_format($recebimentosMesAtual, 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Recebido Este Mês</p>
                        @if($crescimentoPercentual != 0)
                        <small class="text-{{ $crescimentoPercentual > 0 ? 'success' : 'danger' }}">
                            <i class="ri-arrow-{{ $crescimentoPercentual > 0 ? 'up' : 'down' }}-line"></i>
                            {{ abs($crescimentoPercentual) }}% vs mês anterior
                        </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Faturas e Assinaturas -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                            <i class="ri-file-list-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ $faturasPagas }}">{{ $faturasPagas }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Faturas Pagas</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.financeiro.faturas', ['status' => 'CONFIRMED']) }}" class="btn btn-sm btn-primary">Ver Faturas</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-warning bg-light-subtle rounded-2 fs-4">
                            <i class="ri-file-warning-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ $faturasPendentes }}">{{ $faturasPendentes }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Faturas Pendentes</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.financeiro.faturas', ['status' => 'PENDING']) }}" class="btn btn-sm btn-warning">Ver Pendentes</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-success bg-light-subtle rounded-2 fs-4">
                            <i class="ri-refresh-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ $assinaturasAtivas }}">{{ $assinaturasAtivas }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Assinaturas Ativas</p>
                        <div class="mt-3">
                            <small class="text-muted">{{ $assinaturasSuspensas }} suspensas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Recebimentos -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Recebimentos dos Últimos 12 Meses</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="recebimentosChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Recebimentos por Método (30 dias)</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="metodosChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Rápidos -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Ações Rápidas</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.financeiro.pagamentos') }}" class="btn btn-outline-primary w-100">
                                    <i class="ri-money-dollar-circle-line me-2"></i>
                                    Todos os Pagamentos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.financeiro.faturas') }}" class="btn btn-outline-info w-100">
                                    <i class="ri-file-list-line me-2"></i>
                                    Todas as Faturas
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.financeiro.relatorio') }}" class="btn btn-outline-success w-100">
                                    <i class="ri-bar-chart-line me-2"></i>
                                    Relatório Completo
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.financeiro.relatorio', ['data_inicio' => now()->startOfMonth()->format('Y-m-d'), 'data_fim' => now()->endOfMonth()->format('Y-m-d')]) }}" class="btn btn-outline-warning w-100">
                                    <i class="ri-calendar-line me-2"></i>
                                    Relatório do Mês
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Recebimentos dos Últimos 12 Meses
    const recebimentosData = @json($recebimentosUltimos12Meses);
    const ctxRecebimentos = document.getElementById('recebimentosChart').getContext('2d');
    
    new Chart(ctxRecebimentos, {
        type: 'line',
        data: {
            labels: recebimentosData.map(item => item.mes),
            datasets: [{
                label: 'Recebimentos (R$)',
                data: recebimentosData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Recebimentos: R$ ' + context.parsed.y.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Recebimentos por Método
    const metodosData = @json($recebimentosPorMetodo);
    const ctxMetodos = document.getElementById('metodosChart').getContext('2d');
    
    new Chart(ctxMetodos, {
        type: 'doughnut',
        data: {
            labels: metodosData.map(item => item.payment_method || 'Não informado'),
            datasets: [{
                data: metodosData.map(item => item.total),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': R$ ' + context.parsed.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
