@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Relatório Financeiro</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.financeiro.index') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Relatório</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Filtros do Relatório</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.financeiro.relatorio') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ $dataInicio }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ $dataFim }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Gerar Relatório
                            </button>
                            <button type="button" class="btn btn-success me-2" onclick="exportarRelatorio()">
                                <i class="ri-download-line me-1"></i>Exportar PDF
                            </button>
                            <a href="{{ route('admin.financeiro.relatorio') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Limpar Filtros
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resumo Geral -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-success bg-light-subtle rounded-2 fs-4">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($recebimentos->sum('value'), 2, ',', '.') }}">R$ {{ number_format($recebimentos->sum('value'), 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Total Recebido</p>
                        <small class="text-muted">{{ $recebimentos->count() }} pagamentos</small>
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
                            <span class="counter-value" data-target="{{ $recebimentosPorDia->count() }}">{{ $recebimentosPorDia->count() }}</span>
                        </h2>
 <p class="mb-0 text-truncate fs-16 mb-1">Dias com Recebimento</p>
                        <small class="text-muted">Período selecionado</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-warning bg-light-subtle rounded-2 fs-4">
                            <i class="ri-bar-chart-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ number_format($recebimentos->avg('value'), 2, ',', '.') }}">R$ {{ number_format($recebimentos->avg('value'), 2, ',', '.') }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Ticket Médio</p>
                        <small class="text-muted">Por pagamento</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border shadow-none mb-0">
                    <div class="card-body">
                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                            <i class="ri-user-line"></i>
                        </div>
                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                            <span class="counter-value" data-target="{{ $recebimentos->unique('user_id')->count() }}">{{ $recebimentos->unique('user_id')->count() }}</span>
                        </h2>
                        <p class="mb-0 text-truncate fs-16 mb-1">Associados Únicos</p>
                        <small class="text-muted">Com pagamentos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo por Status -->
        <div class="row mb-4">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Resumo por Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Quantidade</th>
                                        <th>Valor Total</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resumoStatus as $item)
                                    @php
                                        $totalGeral = $resumoStatus->sum('total');
                                        $percentual = $totalGeral > 0 ? ($item->total / $totalGeral) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            @php
                                                $statusClass = 'info';
                                                switch($item->status) {
                                                    case 'CONFIRMED':
                                                    case 'RECEIVED_IN_CASH':
                                                    case 'RECEIVED_WITH_OVERDUE':
                                                        $statusClass = 'success';
                                                        break;
                                                    case 'PENDING':
                                                        $statusClass = 'warning';
                                                        break;
                                                    case 'OVERDUE':
                                                        $statusClass = 'danger';
                                                        break;
                                                    case 'REFUNDED':
                                                        $statusClass = 'secondary';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $item->status }}</span>
                                        </td>
                                        <td>{{ $item->quantidade }}</td>
                                        <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                                        <td>{{ number_format($percentual, 1) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Resumo por Método de Pagamento</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Método</th>
                                        <th>Quantidade</th>
                                        <th>Valor Total</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resumoMetodo as $item)
                                    @php
                                        $totalMetodos = $resumoMetodo->sum('total');
                                        $percentualMetodo = $totalMetodos > 0 ? ($item->total / $totalMetodos) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->payment_method ?? 'Não informado' }}</td>
                                        <td>{{ $item->quantidade }}</td>
                                        <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                                        <td>{{ number_format($percentualMetodo, 1) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Recebimentos por Dia -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Recebimentos por Dia</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="recebimentosPorDiaChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Detalhada de Recebimentos -->
        <div class="card">
            <div class="card-header pb-0">
                <h4>Lista Detalhada de Recebimentos</h4>
                <p class="text-muted mb-0">Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Associado</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Método</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recebimentos as $recebimento)
                            <tr>
                                <td>{{ $recebimento->formatted_payment_date }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $recebimento->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $recebimento->user->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $recebimento->formatted_value }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'info';
                                        switch($recebimento->status) {
                                            case 'CONFIRMED':
                                            case 'RECEIVED_IN_CASH':
                                            case 'RECEIVED_WITH_OVERDUE':
                                                $statusClass = 'success';
                                                break;
                                            case 'PENDING':
                                                $statusClass = 'warning';
                                                break;
                                            case 'OVERDUE':
                                                $statusClass = 'danger';
                                                break;
                                            case 'REFUNDED':
                                                $statusClass = 'secondary';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $recebimento->formatted_status }}</span>
                                </td>
                                <td>{{ $recebimento->payment_method ?? 'N/A' }}</td>
                                <td>{{ $recebimento->description ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line fs-48 mb-3 d-block"></i>
                                        Nenhum recebimento encontrado no período selecionado.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Recebimentos por Dia
    const recebimentosPorDiaData = @json($recebimentosPorDia);
    const ctxRecebimentosPorDia = document.getElementById('recebimentosPorDiaChart').getContext('2d');
    
    new Chart(ctxRecebimentosPorDia, {
        type: 'bar',
        data: {
            labels: recebimentosPorDiaData.map(item => {
                const date = new Date(item.data);
                return date.toLocaleDateString('pt-BR');
            }),
            datasets: [{
                label: 'Recebimentos (R$)',
                data: recebimentosPorDiaData.map(item => item.total),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
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
});

function exportarRelatorio() {
    // Implementar exportação para PDF
    alert('Funcionalidade de exportação será implementada em breve!');
}
</script>
@endpush
@endsection
