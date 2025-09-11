@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Gerenciar Solicitações</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitações</li>
                    </ol>
                </nav>
            </div>
        </div>

    <!-- Estatísticas -->
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

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.solicitacoes.index') }}" id="filtros-form">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos os status</option>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="">Todos os tipos</option>
                                    @foreach($tipoOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('tipo') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select class="form-select" id="prioridade" name="prioridade">
                                    <option value="">Todas as prioridades</option>
                                    @foreach($prioridadeOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('prioridade') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="admin_responsavel" class="form-label">Responsável</label>
                                <select class="form-select" id="admin_responsavel" name="admin_responsavel">
                                    <option value="">Todos os responsáveis</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ request('admin_responsavel') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="busca" class="form-label">Buscar</label>
                                <input type="text" class="form-control" id="busca" name="busca" 
                                       placeholder="Título, descrição ou usuário..." value="{{ request('busca') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary"><i class="ri-search-line me-1"></i>Filtrar</button>
                                <a href="{{ route('admin.solicitacoes.index') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line me-1"></i>Limpar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Solicitações -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-3-line me-2"></i>Solicitações dos Associados
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.solicitacoes.dashboard') }}" class="btn btn-outline-info btn-sm">
                                <i class="ri-dashboard-line me-1"></i>Dashboard
                            </a>
                            <span class="text-muted">Total: {{ $solicitacoes->total() }} solicitações</span>
                        </div>
                    </div>
                    @if($solicitacoes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Solicitante</th>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Status</th>
                                        <th>Prioridade</th>
                                        <th>Responsável</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoes as $solicitacao)
                                        <tr class="{{ $solicitacao->atrasada ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <div class="avatar-xs rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                            <i class="ri-file-text-line text-muted" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">#{{ $solicitacao->id }}</div>
                                                        <small class="text-muted">{{ $solicitacao->tempo_decorrido }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        @if($solicitacao->user->photo)
                                                            <img src="{{ $solicitacao->user->photo_url }}" alt="Avatar" class="avatar-xs rounded-circle">
                                                        @else
                                                            <div class="avatar-xs rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="font-size: 0.7rem; font-weight: bold;">
                                                                {{ $solicitacao->user->getInitials() }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $solicitacao->user->name }}</div>
                                                        <small class="text-muted">{{ $solicitacao->user->matricula }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ $solicitacao->tipo_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ str_limit($solicitacao->titulo, 40) }}</div>
                                                <small class="text-muted">{{ str_limit($solicitacao->descricao, 60) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $solicitacao->status_cor }}">
                                                    {{ $solicitacao->status_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $solicitacao->prioridade_cor }}">
                                                    {{ $solicitacao->prioridade_nome }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($solicitacao->admin)
                                                    <div class="fw-medium">{{ $solicitacao->admin->name }}</div>
                                                @else
                                                    <span class="text-muted">Não atribuído</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $solicitacao->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $solicitacao->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.solicitacoes.show', $solicitacao->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    @if(!$solicitacao->admin_responsavel)
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                onclick="assignAdmin({{ $solicitacao->id }})" title="Atribuir Responsável">
                                                            <i class="ri-user-add-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginação -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Mostrando {{ $solicitacoes->firstItem() }} a {{ $solicitacoes->lastItem() }} 
                                de {{ $solicitacoes->total() }} resultados
                            </div>
                            <div>
                                {{ $solicitacoes->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-light text-primary rounded-circle">
                                    <i class="ri-file-list-3-line" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Nenhuma solicitação encontrada</h5>
                            <p class="text-muted mb-4">
                                @if(count($filtros) > 0)
                                    Não foram encontradas solicitações com os filtros aplicados.
                                @else
                                    Ainda não há solicitações registradas no sistema.
                                @endif
                            </p>
                            @if(count($filtros) > 0)
                                <a href="{{ route('admin.solicitacoes.index') }}" class="btn btn-primary">
                                    <i class="ri-refresh-line me-1"></i>Limpar Filtros
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<!-- Modal para Atribuir Admin -->
<div class="modal fade" id="assignAdminModal" tabindex="-1" aria-labelledby="assignAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignAdminModalLabel">Atribuir Responsável</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignAdminForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_responsavel" class="form-label">Selecione o responsável</label>
                        <select class="form-select" id="admin_responsavel" name="admin_responsavel" required>
                            <option value="">Selecione um admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atribuir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit do formulário quando algum filtro mudar
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filtros-form');
        const selects = form.querySelectorAll('select');
        
        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });
    });

    // Função para atribuir admin responsável
    function assignAdmin(solicitacaoId) {
        const form = document.getElementById('assignAdminForm');
        form.action = '{{ route("admin.solicitacoes.assign-admin", ":id") }}'.replace(':id', solicitacaoId);
        
        const modal = new bootstrap.Modal(document.getElementById('assignAdminModal'));
        modal.show();
    }
</script>
@endpush
