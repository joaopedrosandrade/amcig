@extends('layouts.associado')

@section('title', 'Minhas Solicitações - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Minhas Solicitações</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Solicitações</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

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

        <!-- Botão Nova Solicitação -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Suas Solicitações</h5>
                    <a href="{{ route('associado.solicitacoes.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Nova Solicitação
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('associado.solicitacoes.index') }}" id="filtros-form">
                            <div class="row g-3">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <label for="busca" class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="busca" name="busca" 
                                           placeholder="Título ou descrição..." value="{{ request('busca') }}">
                                </div>
                                <div class="col-md-6 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="ri-search-line me-1"></i>Filtrar</button>
                                    <a href="{{ route('associado.solicitacoes.index') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line me-1"></i>Limpar</a>
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
                                <i class="ri-file-list-3-line me-2"></i>Solicitações
                            </h5>
                            <span class="text-muted">Total: {{ $solicitacoes->total() }} solicitações</span>
                        </div>
                        @if($solicitacoes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tipo</th>
                                            <th>Título</th>
                                            <th>Status</th>
                                            <th>Prioridade</th>
                                            <th>Data</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($solicitacoes as $solicitacao)
                                            <tr>
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
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ $solicitacao->tipo_nome }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ str_limit($solicitacao->titulo, 50) }}</div>
                                                    <small class="text-muted">{{ str_limit($solicitacao->descricao, 80) }}</small>
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
                                                    <div class="fw-medium">{{ $solicitacao->created_at->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $solicitacao->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('associado.solicitacoes.show', $solicitacao->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        @if($solicitacao->status === 'ABERTA')
                                                            <form method="POST" action="{{ route('associado.solicitacoes.cancel', $solicitacao->id) }}" 
                                                                  style="display: inline;" 
                                                                  onsubmit="return confirm('Tem certeza que deseja cancelar esta solicitação?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar">
                                                                    <i class="ri-close-line"></i>
                                                                </button>
                                                            </form>
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
                                        Você ainda não possui solicitações registradas.
                                    @endif
                                </p>
                                @if(count($filtros) > 0)
                                    <a href="{{ route('associado.solicitacoes.index') }}" class="btn btn-primary">
                                        <i class="ri-refresh-line me-1"></i>Limpar Filtros
                                    </a>
                                @else
                                    <a href="{{ route('associado.solicitacoes.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i>Nova Solicitação
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
</script>
@endpush
