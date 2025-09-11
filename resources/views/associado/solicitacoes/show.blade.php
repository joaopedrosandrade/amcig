@extends('layouts.associado')

@section('title', 'Detalhes da Solicitação #' . $solicitacao->id . ' - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Solicitação #{{ $solicitacao->id }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('associado.solicitacoes.index') }}">Solicitações</a></li>
                            <li class="breadcrumb-item active">#{{ $solicitacao->id }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

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
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Criada em</small><br>
                                <strong>{{ $solicitacao->created_at->format('d/m/Y H:i') }}</strong>
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
                            <a href="{{ route('associado.solicitacoes.index') }}" class="btn btn-outline-primary">
                                <i class="ri-arrow-left-line me-1"></i>Voltar para Lista
                            </a>
                            @if($solicitacao->status === 'ABERTA')
                                <form method="POST" action="{{ route('associado.solicitacoes.cancel', $solicitacao->id) }}" 
                                      onsubmit="return confirm('Tem certeza que deseja cancelar esta solicitação?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="ri-close-line me-1"></i>Cancelar Solicitação
                                    </button>
                                </form>
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
                                    <p class="mb-0">Sua solicitação foi recebida e está aguardando análise pela administração.</p>
                                </div>
                                @break
                            @case('EM_ANALISE')
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">Em Análise</h6>
                                    <p class="mb-0">Sua solicitação está sendo analisada pela equipe responsável.</p>
                                </div>
                                @break
                            @case('EM_ANDAMENTO')
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Em Andamento</h6>
                                    <p class="mb-0">A equipe está trabalhando na resolução da sua solicitação.</p>
                                </div>
                                @break
                            @case('CONCLUIDA')
                                <div class="alert alert-success">
                                    <h6 class="alert-heading">Concluída</h6>
                                    <p class="mb-0">Sua solicitação foi concluída com sucesso!</p>
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
</main>
@endsection

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
@endpush
