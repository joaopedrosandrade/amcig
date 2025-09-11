@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Dashboard de Faturas</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.financeiro.index') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Faturas</li>
                    </ol>
                </nav>
            </div>
        </div>

    <!-- Estatísticas Principais -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total de Faturas</p>
                            <h4 class="mb-0">{{ $totalFaturas }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-file-list-3-line text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Pendentes</p>
                            <h4 class="mb-0">{{ $faturasPendentes }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-time-line text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Pagas</p>
                            <h4 class="mb-0">{{ $faturasPagas }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-checkbox-circle-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Vencidas</p>
                            <h4 class="mb-0">{{ $faturasVencidas }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-alert-line text-danger" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valores Financeiros -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Valor Pendente</p>
                            <h4 class="mb-0">R$ {{ number_format($valorTotalPendente, 2, ',', '.') }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Valor Recebido</p>
                            <h4 class="mb-0">R$ {{ number_format($valorTotalPago, 2, ',', '.') }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Valor Vencido</p>
                            <h4 class="mb-0">R$ {{ number_format($valorTotalVencido, 2, ',', '.') }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line text-danger" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if($faturasVencidas > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Atenção!</strong> Existem {{ $faturasVencidas }} faturas vencidas que precisam de atenção.
                    <a href="{{ route('admin.financeiro.faturas', ['status' => 'OVERDUE']) }}" class="alert-link">Ver faturas vencidas</a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Faturas Vencidas -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-alert-line me-2 text-danger"></i>Faturas Vencidas
                    </h5>
                    @if($faturasVencidasList->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Associado</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturasVencidasList->take(5) as $fatura)
                                        <tr>
                                            <td>#{{ $fatura->id }}</td>
                                            <td>{{ $fatura->user->name ?? 'N/A' }}</td>
                                            <td>{{ $fatura->formatted_value }}</td>
                                            <td>{{ $fatura->formatted_due_date }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFatura{{ $fatura->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($faturasVencidasList->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.financeiro.faturas', ['status' => 'OVERDUE']) }}" class="btn btn-sm btn-outline-danger">
                                    Ver todas as faturas vencidas ({{ $faturasVencidasList->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="ri-checkbox-circle-line text-success" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhuma fatura vencida no momento</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Faturas Recentes -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-time-line me-2 text-primary"></i>Faturas Recentes
                    </h5>
                    @if($faturasRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Associado</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturasRecentes->take(5) as $fatura)
                                        @php
                                            $statusClass = 'info';
                                            switch($fatura->status) {
                                                case 'CONFIRMED':
                                                case 'RECEIVED':
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
                                        <tr>
                                            <td>#{{ $fatura->id }}</td>
                                            <td>{{ $fatura->user->name ?? 'N/A' }}</td>
                                            <td>{{ $fatura->formatted_value }}</td>
                                            <td>
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $fatura->formatted_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFatura{{ $fatura->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ri-file-list-3-line text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhuma fatura recente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Faturas por Status -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-bar-chart-line me-2 text-info"></i>Faturas por Status
                    </h5>
                    @if($faturasPorStatus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Quantidade</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturasPorStatus as $item)
                                        @php
                                            $percentage = $totalFaturas > 0 ? round(($item->total / $totalFaturas) * 100, 1) : 0;
                                            $statusClass = 'info';
                                            switch($item->status) {
                                                case 'CONFIRMED':
                                                case 'RECEIVED':
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
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $statusOptions[$item->status] ?? $item->status }}
                                                </span>
                                            </td>
                                            <td>{{ $item->total }}</td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $statusClass }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $percentage }}%</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ri-bar-chart-line text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhum dado disponível</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-flashlight-line me-2 text-success"></i>Ações Rápidas
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.financeiro.faturas', ['status' => 'PENDING']) }}" 
                           class="btn btn-outline-warning">
                            <i class="ri-time-line me-1"></i>Faturas Pendentes ({{ $faturasPendentes }})
                        </a>
                        <a href="{{ route('admin.financeiro.faturas', ['status' => 'OVERDUE']) }}" 
                           class="btn btn-outline-danger">
                            <i class="ri-alert-line me-1"></i>Faturas Vencidas ({{ $faturasVencidas }})
                        </a>
                        <a href="{{ route('admin.financeiro.faturas', ['status' => 'CONFIRMED']) }}" 
                           class="btn btn-outline-success">
                            <i class="ri-checkbox-circle-line me-1"></i>Faturas Pagas ({{ $faturasPagas }})
                        </a>
                        <a href="{{ route('admin.financeiro.faturas') }}" 
                           class="btn btn-outline-primary">
                            <i class="ri-file-list-3-line me-1"></i>Todas as Faturas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-search-line me-2"></i>Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.financeiro.faturas') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Todos os Status</option>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_inicio" class="form-label">Data Vencimento Início</label>
                                <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_fim" class="form-label">Data Vencimento Fim</label>
                                <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('admin.financeiro.faturas') }}" class="btn btn-outline-secondary">
                                    <i class="ri-refresh-line me-1"></i>Limpar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Faturas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4>Lista de Faturas</h4>
                    <p class="text-muted mb-0">Total de {{ $faturas->total() }} faturas encontradas</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Associado</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Vencimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faturas as $fatura)
                                <tr>
                                    <td>{{ $fatura->id }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $fatura->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $fatura->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $fatura->formatted_value }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'info';
                                            switch($fatura->status) {
                                                case 'CONFIRMED':
                                                case 'RECEIVED':
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
                                        <span class="badge bg-{{ $statusClass }}">{{ $fatura->formatted_status }}</span>
                                    </td>
                                    <td>
                                        {{ $fatura->formatted_due_date }}
                                        @if($fatura->isOverdue())
                                            <br><small class="text-danger">Vencida há {{ $fatura->due_date->diffInDays(now()) }} dias</small>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFatura{{ $fatura->id }}">
                                            <i class="ri-eye-line me-1"></i>Ver Detalhes
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-48 mb-3 d-block"></i>
                                            Nenhuma fatura encontrada com os filtros aplicados.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($faturas->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $faturas->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<!-- Modais de Detalhes das Faturas -->
@foreach($faturas as $fatura)
<div class="modal fade" id="modalFatura{{ $fatura->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Fatura #{{ $fatura->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="faturaTabs{{ $fatura->id }}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="dados-tab{{ $fatura->id }}" data-bs-toggle="tab" data-bs-target="#dados{{ $fatura->id }}" type="button" role="tab" aria-controls="dados{{ $fatura->id }}" aria-selected="true">
                            <i class="ri-file-text-line me-1"></i>Dados da Fatura
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="asaas-tab{{ $fatura->id }}" data-bs-toggle="tab" data-bs-target="#asaas{{ $fatura->id }}" type="button" role="tab" aria-controls="asaas{{ $fatura->id }}" aria-selected="false">
                            <i class="ri-code-line me-1"></i>Retorno Asaas
                        </button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content mt-3" id="faturaTabContent{{ $fatura->id }}">
                    <!-- Dados da Fatura Tab -->
                    <div class="tab-pane fade show active" id="dados{{ $fatura->id }}" role="tabpanel" aria-labelledby="dados-tab{{ $fatura->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informações da Fatura</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>{{ $fatura->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valor:</strong></td>
                                        <td>{{ $fatura->formatted_value }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @php
                                                $statusClass = 'info';
                                                switch($fatura->status) {
                                                    case 'CONFIRMED':
                                                    case 'RECEIVED':
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
                                            <span class="badge bg-{{ $statusClass }}">{{ $fatura->formatted_status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Vencimento:</strong></td>
                                        <td>{{ $fatura->formatted_due_date }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pagamento:</strong></td>
                                        <td>{{ $fatura->formatted_payment_date ?? 'Não paga' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo Cobrança:</strong></td>
                                        <td>{{ $fatura->billing_type ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descrição:</strong></td>
                                        <td>{{ $fatura->description ?? 'N/A' }}</td>
                                    </tr>
                                    @if($fatura->invoice_url)
                                    <tr>
                                        <td><strong>Link da Fatura:</strong></td>
                                        <td>
                                            <a href="{{ $fatura->invoice_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-external-link-line me-1"></i>Abrir Fatura Externa
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Informações do Associado</h6>
                                @if($fatura->user)
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nome:</strong></td>
                                        <td>{{ $fatura->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $fatura->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Matrícula:</strong></td>
                                        <td>{{ $fatura->user->matricula ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>{{ ucfirst($fatura->user->tipo_associado ?? 'N/A') }}</td>
                                    </tr>
                                </table>
                                @else
                                <p class="text-muted">Associado não encontrado</p>
                                @endif

                                @if($fatura->subscription)
                                <h6 class="mt-3">Assinatura</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>{{ $fatura->subscription->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>{{ $fatura->subscription->status }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Próximo Vencimento:</strong></td>
                                        <td>{{ $fatura->subscription->formatted_next_due_date }}</td>
                                    </tr>
                                </table>
                                @endif
                            </div>
                        </div>

                        @if($fatura->pix_qr_code || $fatura->pix_copy_paste)
                        <div class="mt-3">
                            <h6>Informações PIX</h6>
                            @if($fatura->pix_qr_code)
                            <div class="mb-2">
                                <strong>QR Code:</strong><br>
                                <img src="{{ $fatura->pix_qr_code }}" alt="PIX QR Code" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @endif
                            @if($fatura->pix_copy_paste)
                            <div class="mb-2">
                                <strong>Chave PIX:</strong><br>
                                <code class="bg-light p-2 rounded d-block">{{ $fatura->pix_copy_paste }}</code>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Retorno Asaas Tab -->
                    <div class="tab-pane fade" id="asaas{{ $fatura->id }}" role="tabpanel" aria-labelledby="asaas-tab{{ $fatura->id }}">
                        @if($fatura->asaas_data)
                        <div class="mb-3">
                            <h6>Dados do Asaas</h6>
                            <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                                <pre class="mb-0"><code>{{ json_encode($fatura->asaas_data, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="ri-inbox-line fs-48 text-muted mb-3 d-block"></i>
                            <p class="text-muted">Nenhum dado do Asaas disponível para esta fatura.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection