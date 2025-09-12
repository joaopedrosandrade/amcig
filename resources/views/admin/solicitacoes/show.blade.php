@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Solicitação #{{ $solicitacao->id }}</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.solicitacoes.index') }}">Solicitações</a></li>
                        <li class="breadcrumb-item active" aria-current="page">#{{ $solicitacao->id }}</li>
                    </ol>
                </nav>
            </div>
        </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Informações Principais -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h5 class="card-title mb-2">{{ $solicitacao->titulo }}</h5>
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge bg-{{ $solicitacao->status_cor }} fs-6">
                                    {{ $solicitacao->status_nome }}
                                </span>
                                <span class="badge bg-{{ $solicitacao->prioridade_cor }} fs-6">
                                    {{ $solicitacao->prioridade_nome }}
                                </span>
                                <span class="badge bg-info-subtle text-info fs-6">
                                    {{ $solicitacao->tipo_nome }}
                                </span>
                                @if($solicitacao->atrasada)
                                    <span class="badge bg-danger fs-6">Atrasada</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Criada em</small><br>
                            <strong>{{ $solicitacao->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    </div>

                    <!-- Informações do Solicitante -->
                    <div class="mb-4">
                        <h6 class="mb-2">Solicitante</h6>
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if($solicitacao->user->photo)
                                        <img src="{{ $solicitacao->user->photo_url }}" alt="Avatar" class="avatar-md rounded-circle">
                                    @else
                                        <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="font-size: 16px; font-weight: bold;">
                                            {{ $solicitacao->user->getInitials() }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $solicitacao->user->name }}</h6>
                                    <p class="text-muted mb-1">Matrícula: {{ $solicitacao->user->matricula }}</p>
                                    <p class="text-muted mb-0">Email: {{ $solicitacao->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <h6 class="mb-2">Descrição</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $solicitacao->descricao }}
                        </div>
                    </div>

                    <!-- Localização -->
                    <div class="mb-4">
                        <h6 class="mb-2">Localização</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ri-map-pin-line text-primary me-2"></i>
                                        <strong>{{ $solicitacao->endereco }}</strong>
                                    </div>
                                    @if($solicitacao->bairro)
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ri-community-line text-muted me-2"></i>
                                            <span>{{ $solicitacao->bairro }}</span>
                                        </div>
                                    @endif
                                    @if($solicitacao->cep)
                                        <div class="d-flex align-items-center">
                                            <i class="ri-mail-line text-muted me-2"></i>
                                            <span>{{ $solicitacao->cep }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if($solicitacao->latitude && $solicitacao->longitude)
                                    <div id="map" style="height: 200px; border-radius: 8px;"></div>
                                @else
                                    <div class="bg-light p-3 rounded text-center">
                                        <i class="ri-map-pin-line text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Localização não informada</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Observações do Admin -->
                    @if($solicitacao->observacoes_admin)
                        <div class="mb-4">
                            <h6 class="mb-2">Observações da Administração</h6>
                            <div class="alert alert-info">
                                {{ $solicitacao->observacoes_admin }}
                            </div>
                        </div>
                    @endif

                    <!-- Datas Importantes -->
                    <div class="row">
                        @if($solicitacao->data_limite)
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="ri-time-line me-1"></i>Data Limite
                                        </h6>
                                        <p class="card-text">
                                            {{ $solicitacao->data_limite->format('d/m/Y H:i') }}
                                            @if($solicitacao->atrasada)
                                                <span class="badge bg-danger ms-2">Atrasada</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($solicitacao->data_conclusao)
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            <i class="ri-checkbox-circle-line me-1"></i>Data de Conclusão
                                        </h6>
                                        <p class="card-text">{{ $solicitacao->data_conclusao->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Painel Lateral -->
        <div class="col-lg-4">
            <!-- Ações -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-settings-3-line me-2"></i>Ações
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.solicitacoes.index') }}" class="btn btn-outline-primary">
                            <i class="ri-arrow-left-line me-1"></i>Voltar para Lista
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="ri-edit-line me-1"></i>Atualizar Status
                        </button>
                        @if(!$solicitacao->admin_responsavel)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignAdminModal">
                                <i class="ri-user-add-line me-1"></i>Atribuir Responsável
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações do Status -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-information-line me-2"></i>Status da Solicitação
                    </h5>
                    
                    @switch($solicitacao->status)
                        @case('ABERTA')
                            <div class="alert alert-primary">
                                <h6 class="alert-heading">Solicitação Aberta</h6>
                                <p class="mb-0">A solicitação foi recebida e está aguardando análise pela administração.</p>
                            </div>
                            @break
                        @case('EM_ANALISE')
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">Em Análise</h6>
                                <p class="mb-0">A solicitação está sendo analisada pela equipe responsável.</p>
                            </div>
                            @break
                        @case('EM_ANDAMENTO')
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Em Andamento</h6>
                                <p class="mb-0">A equipe está trabalhando na resolução da solicitação.</p>
                            </div>
                            @break
                        @case('CONCLUIDA')
                            <div class="alert alert-success">
                                <h6 class="alert-heading">Concluída</h6>
                                <p class="mb-0">A solicitação foi concluída com sucesso!</p>
                            </div>
                            @break
                        @case('CANCELADA')
                            <div class="alert alert-secondary">
                                <h6 class="alert-heading">Cancelada</h6>
                                <p class="mb-0">Esta solicitação foi cancelada.</p>
                            </div>
                            @break
                        @case('REJEITADA')
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">Rejeitada</h6>
                                <p class="mb-0">Esta solicitação foi rejeitada pela administração.</p>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>

            <!-- Informações Técnicas -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-code-line me-2"></i>Informações Técnicas
                    </h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span>ID:</span>
                            <strong>#{{ $solicitacao->id }}</strong>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span>Criada em:</span>
                            <strong>{{ $solicitacao->created_at->format('d/m/Y') }}</strong>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span>Última atualização:</span>
                            <strong>{{ $solicitacao->updated_at->format('d/m/Y') }}</strong>
                        </div>
                        @if($solicitacao->admin)
                            <div class="list-group-item px-0 d-flex justify-content-between">
                                <span>Responsável:</span>
                                <strong>{{ $solicitacao->admin->name }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Atualizar Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Atualizar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm" method="POST" action="{{ route('admin.solicitacoes.update-status', $solicitacao->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="ABERTA" {{ $solicitacao->status == 'ABERTA' ? 'selected' : '' }}>Aberta</option>
                            <option value="EM_ANALISE" {{ $solicitacao->status == 'EM_ANALISE' ? 'selected' : '' }}>Em Análise</option>
                            <option value="EM_ANDAMENTO" {{ $solicitacao->status == 'EM_ANDAMENTO' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="CONCLUIDA" {{ $solicitacao->status == 'CONCLUIDA' ? 'selected' : '' }}>Concluída</option>
                            <option value="CANCELADA" {{ $solicitacao->status == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                            <option value="REJEITADA" {{ $solicitacao->status == 'REJEITADA' ? 'selected' : '' }}>Rejeitada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="data_limite" class="form-label">Data Limite (opcional)</label>
                        <input type="datetime-local" class="form-control" id="data_limite" name="data_limite" 
                               value="{{ $solicitacao->data_limite ? $solicitacao->data_limite->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="admin_responsavel" class="form-label">Responsável (opcional)</label>
                        <select class="form-select" id="admin_responsavel" name="admin_responsavel">
                            <option value="">Selecione um admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $solicitacao->admin_responsavel == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes_admin" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes_admin" name="observacoes_admin" rows="3" 
                                  placeholder="Adicione observações sobre o andamento da solicitação...">{{ $solicitacao->observacoes_admin }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarAtualizacao">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarAtualizacao">
                        <span class="btn-text">Atualizar</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Processando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Atribuir Admin -->
<div class="modal fade" id="assignAdminModal" tabindex="-1" aria-labelledby="assignAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignAdminModalLabel">Atribuir Responsável</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.solicitacoes.assign-admin', $solicitacao->id) }}">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarAtribuicao">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarAtribuicao">
                        <span class="btn-text">Atribuir</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Processando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

<!-- Modal de carregamento -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <h5 class="mb-2">Processando solicitação...</h5>
                <p class="text-muted mb-0">Por favor, aguarde enquanto processamos sua solicitação.</p>
            </div>
        </div>
    </div>
</div>

</main>
@endsection

@push('styles')
<style>
    .btn-loading {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    #loadingModal .modal-content {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    #loadingModal .spinner-border {
        border-width: 0.3em;
    }
</style>
@endpush

@push('scripts')
@if($solicitacao->latitude && $solicitacao->longitude)
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa
    const map = L.map('map').setView([{{ $solicitacao->latitude }}, {{ $solicitacao->longitude }}], 16);
    
    // Adicionar camada de tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Adicionar marcador
    L.marker([{{ $solicitacao->latitude }}, {{ $solicitacao->longitude }}])
        .addTo(map)
        .bindPopup('{{ $solicitacao->endereco }}')
        .openPopup();
});
</script>
@endif

<script>
$(document).ready(function() {
    // Função para mostrar estado de carregamento
    function mostrarCarregamento(btnId, isLoading) {
        const btn = document.getElementById(btnId);
        const btnText = btn.querySelector('.btn-text');
        const btnLoading = btn.querySelector('.btn-loading');
        
        if (isLoading) {
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        } else {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
        }
    }

    // Função para mostrar modal de carregamento
    function mostrarModalCarregamento() {
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        return loadingModal;
    }

    // Função para esconder modal de carregamento
    function esconderModalCarregamento(modal) {
        modal.hide();
    }

    // Evento para atualizar status
    $('#confirmarAtualizacao').click(function() {
        // Validar campos obrigatórios
        const status = $('#status').val();
        if (!status) {
            alert('Por favor, selecione um status.');
            return;
        }

        // Mostrar estado de carregamento no botão
        mostrarCarregamento('confirmarAtualizacao', true);
        
        // Mostrar modal de carregamento
        const loadingModal = mostrarModalCarregamento();
        
        // Fechar modal de atualização
        $('#updateStatusModal').modal('hide');
        
        // Submeter formulário via AJAX
        $.ajax({
            url: '{{ route("admin.solicitacoes.update-status", $solicitacao->id) }}',
            type: 'POST',
            data: $('#updateStatusForm').serialize(),
            success: function(response) {
                // Mostrar mensagem de sucesso
                setTimeout(function() {
                    esconderModalCarregamento(loadingModal);
                    // Recarrega a página para atualizar os dados
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                esconderModalCarregamento(loadingModal);
                mostrarCarregamento('confirmarAtualizacao', false);
                
                if (xhr.status === 422) {
                    // Erro de validação
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erro de validação:\n';
                    for (let field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                    alert(errorMessage);
                } else {
                    alert('Erro ao atualizar status da solicitação.');
                }
            }
        });
    });

    // Evento para atribuir admin
    $('#confirmarAtribuicao').click(function() {
        // Validar campos obrigatórios
        const adminResponsavel = $('#assignAdminModal select[name="admin_responsavel"]').val();
        if (!adminResponsavel) {
            alert('Por favor, selecione um admin responsável.');
            return;
        }

        // Mostrar estado de carregamento no botão
        mostrarCarregamento('confirmarAtribuicao', true);
        
        // Mostrar modal de carregamento
        const loadingModal = mostrarModalCarregamento();
        
        // Fechar modal de atribuição
        $('#assignAdminModal').modal('hide');
        
        // Submeter formulário via AJAX
        $.ajax({
            url: '{{ route("admin.solicitacoes.assign-admin", $solicitacao->id) }}',
            type: 'POST',
            data: $('#assignAdminModal form').serialize(),
            success: function(response) {
                // Mostrar mensagem de sucesso
                setTimeout(function() {
                    esconderModalCarregamento(loadingModal);
                    // Recarrega a página para atualizar os dados
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                esconderModalCarregamento(loadingModal);
                mostrarCarregamento('confirmarAtribuicao', false);
                
                if (xhr.status === 422) {
                    // Erro de validação
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erro de validação:\n';
                    for (let field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                    alert(errorMessage);
                } else {
                    alert('Erro ao atribuir responsável à solicitação.');
                }
            }
        });
    });

    // Resetar botões quando modais são fechados
    $('#updateStatusModal').on('hidden.bs.modal', function () {
        mostrarCarregamento('confirmarAtualizacao', false);
    });

    $('#assignAdminModal').on('hidden.bs.modal', function () {
        mostrarCarregamento('confirmarAtribuicao', false);
    });
});
</script>
@endpush
