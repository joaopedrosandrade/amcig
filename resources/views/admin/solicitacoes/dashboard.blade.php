@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Dashboard de Solicitações</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitações</li>
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
                            <p class="text-truncate font-size-14 mb-2">Total de Solicitações</p>
                            <h4 class="mb-0">{{ $totalSolicitacoes }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Abertas</p>
                            <h4 class="mb-0">{{ $solicitacoesAbertas }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-add-circle-line text-primary" style="font-size: 1.5rem;"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Em Andamento</p>
                            <h4 class="mb-0">{{ $solicitacoesEmAndamento }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Concluídas</p>
                            <h4 class="mb-0">{{ $solicitacoesConcluidas }}</h4>
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
    </div>

    <!-- Alertas -->
    @if($solicitacoesAtrasadas > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Atenção!</strong> Existem {{ $solicitacoesAtrasadas }} solicitações atrasadas que precisam de atenção.
                    <a href="{{ route('admin.solicitacoes.index', ['status' => 'EM_ANDAMENTO']) }}" class="alert-link">Ver solicitações atrasadas</a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Solicitações Urgentes -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-alarm-warning-line me-2 text-danger"></i>Solicitações Urgentes
                    </h5>
                    @if($solicitacoesUrgentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Solicitante</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesUrgentes as $solicitacao)
                                        <tr>
                                            <td>#{{ $solicitacao->id }}</td>
                                            <td>{{ $solicitacao->user->name }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ $solicitacao->tipo_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $solicitacao->status_cor }}">
                                                    {{ $solicitacao->status_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.solicitacoes.show', $solicitacao->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ri-checkbox-circle-line text-success" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhuma solicitação urgente no momento</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Solicitações Recentes -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-time-line me-2 text-primary"></i>Solicitações Recentes
                    </h5>
                    @if($solicitacoesRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Solicitante</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesRecentes as $solicitacao)
                                        <tr>
                                            <td>#{{ $solicitacao->id }}</td>
                                            <td>{{ $solicitacao->user->name }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ $solicitacao->tipo_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $solicitacao->status_cor }}">
                                                    {{ $solicitacao->status_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.solicitacoes.show', $solicitacao->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ri-file-list-3-line text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhuma solicitação recente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Solicitações por Tipo -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-bar-chart-line me-2 text-info"></i>Solicitações por Tipo
                    </h5>
                    @if($solicitacoesPorTipo->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Quantidade</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesPorTipo as $item)
                                        @php
                                            $percentage = $totalSolicitacoes > 0 ? round(($item->total / $totalSolicitacoes) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->tipo_nome ?? $item->tipo }}</td>
                                            <td>{{ $item->total }}</td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
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

        <!-- Solicitações por Prioridade -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-pie-chart-line me-2 text-warning"></i>Solicitações por Prioridade
                    </h5>
                    @if($solicitacoesPorPrioridade->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Prioridade</th>
                                        <th>Quantidade</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesPorPrioridade as $item)
                                        @php
                                            $percentage = $totalSolicitacoes > 0 ? round(($item->total / $totalSolicitacoes) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $item->prioridade_cor ?? 'secondary' }}">
                                                    {{ $item->prioridade_nome ?? $item->prioridade }}
                                                </span>
                                            </td>
                                            <td>{{ $item->total }}</td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $item->prioridade_cor ?? 'secondary' }}" 
                                                         role="progressbar" style="width: {{ $percentage }}%"></div>
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
                            <i class="ri-pie-chart-line text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Nenhum dado disponível</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-flashlight-line me-2 text-success"></i>Ações Rápidas
                    </h5>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitacoes.index', ['status' => 'ABERTA']) }}" 
                               class="btn btn-outline-primary w-100">
                                <i class="ri-add-circle-line me-1"></i>Solicitações Abertas
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitacoes.index', ['prioridade' => 'URGENTE']) }}" 
                               class="btn btn-outline-danger w-100">
                                <i class="ri-alarm-warning-line me-1"></i>Solicitações Urgentes
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitacoes.index', ['status' => 'EM_ANDAMENTO']) }}" 
                               class="btn btn-outline-warning w-100">
                                <i class="ri-time-line me-1"></i>Em Andamento
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitacoes.index') }}" 
                               class="btn btn-outline-info w-100">
                                <i class="ri-file-list-3-line me-1"></i>Todas as Solicitações
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection
