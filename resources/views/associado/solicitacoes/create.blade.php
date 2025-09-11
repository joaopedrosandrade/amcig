@extends('layouts.associado')

@section('title', 'Nova Solicitação - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Nova Solicitação</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('associado.solicitacoes.index') }}">Solicitações</a></li>
                            <li class="breadcrumb-item active">Nova</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="ri-add-circle-line me-2"></i>Informações da Solicitação
                        </h5>
                        
                        <form method="POST" action="{{ route('associado.solicitacoes.store') }}" id="solicitacao-form">
                            @csrf
                            
                            <div class="row g-3">
                                <!-- Tipo de Solicitação -->
                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">Tipo de Solicitação <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('tipo') ? 'is-invalid' : '' }}" id="tipo" name="tipo" required>
                                        <option value="">Selecione o tipo</option>
                                        @foreach($tipoOptions as $value => $label)
                                            <option value="{{ $value }}" {{ old('tipo') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('tipo'))
                                        <div class="invalid-feedback">{{ $errors->first('tipo') }}</div>
                                    @endif
                                </div>

                                <!-- Prioridade -->
                                <div class="col-md-6">
                                    <label for="prioridade" class="form-label">Prioridade <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('prioridade') ? 'is-invalid' : '' }}" id="prioridade" name="prioridade" required>
                                        <option value="">Selecione a prioridade</option>
                                        @foreach($prioridadeOptions as $value => $label)
                                            <option value="{{ $value }}" {{ old('prioridade') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('prioridade'))
                                        <div class="invalid-feedback">{{ $errors->first('prioridade') }}</div>
                                    @endif
                                </div>

                                <!-- Título -->
                                <div class="col-12">
                                    <label for="titulo" class="form-label">Título da Solicitação <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('titulo') ? 'is-invalid' : '' }}" 
                                           id="titulo" name="titulo" placeholder="Ex: Falta de iluminação na Rua das Flores" 
                                           value="{{ old('titulo') }}" required maxlength="255">
                                    @if($errors->has('titulo'))
                                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                                    @endif
                                </div>

                                <!-- Descrição -->
                                <div class="col-12">
                                    <label for="descricao" class="form-label">Descrição Detalhada <span class="text-danger">*</span></label>
                                    <textarea class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}" 
                                              id="descricao" name="descricao" rows="4" 
                                              placeholder="Descreva detalhadamente a situação, incluindo informações relevantes como horários, frequência, impacto na comunidade, etc." 
                                              required>{{ old('descricao') }}</textarea>
                                    <div class="form-text">Mínimo de 10 caracteres. Seja específico para facilitar a análise.</div>
                                    @if($errors->has('descricao'))
                                        <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                                    @endif
                                </div>

                                <!-- Endereço -->
                                <div class="col-md-8">
                                    <label for="endereco" class="form-label">Endereço Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('endereco') ? 'is-invalid' : '' }}" 
                                           id="endereco" name="endereco" placeholder="Ex: Rua das Flores, 123" 
                                           value="{{ old('endereco') }}" required maxlength="255">
                                    @if($errors->has('endereco'))
                                        <div class="invalid-feedback">{{ $errors->first('endereco') }}</div>
                                    @endif
                                </div>

                                <!-- Bairro -->
                                <div class="col-md-4">
                                    <label for="bairro" class="form-label">Bairro/Região</label>
                                    <input type="text" class="form-control {{ $errors->has('bairro') ? 'is-invalid' : '' }}" 
                                           id="bairro" name="bairro" placeholder="Ex: Guriri Norte" 
                                           value="{{ old('bairro') }}" maxlength="100">
                                    @if($errors->has('bairro'))
                                        <div class="invalid-feedback">{{ $errors->first('bairro') }}</div>
                                    @endif
                                </div>

                                <!-- CEP -->
                                <div class="col-md-4">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control {{ $errors->has('cep') ? 'is-invalid' : '' }}" 
                                           id="cep" name="cep" placeholder="00000-000" 
                                           value="{{ old('cep') }}" maxlength="9">
                                    @if($errors->has('cep'))
                                        <div class="invalid-feedback">{{ $errors->first('cep') }}</div>
                                    @endif
                                </div>

                                <!-- Coordenadas (serão preenchidas automaticamente pelo mapa) -->
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                            </div>

                            <!-- Botões -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <span class="btn-text">
                                                <i class="ri-save-line me-1"></i>Enviar Solicitação
                                            </span>
                                            <span class="btn-loading d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Enviando...
                                            </span>
                                        </button>
                                        <a href="{{ route('associado.solicitacoes.index') }}" class="btn btn-outline-secondary">
                                            <i class="ri-arrow-left-line me-1"></i>Voltar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Painel Lateral -->
            <div class="col-lg-4">
                <!-- Mapa de Localização -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-map-pin-line me-2"></i>Localização
                        </h5>
                        <div id="map" style="height: 300px; border-radius: 8px;"></div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i>
                                Clique no mapa para marcar a localização exata da solicitação. (Arraste para a direita para encontrar a regoão de Guriri)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Informações Importantes -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line me-2"></i>Informações Importantes
                        </h5>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Antes de enviar:</h6>
                            <ul class="mb-0">
                                <li>Verifique se a localização está correta</li>
                                <li>Seja específico na descrição</li>
                                <li>Inclua horários e frequência se aplicável</li>
                                <li>Mencione o impacto na comunidade</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tipos de Solicitação -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-list-check me-2"></i>Tipos Disponíveis
                        </h5>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-police-car-line text-primary me-2"></i>
                                    <small>Patrulhamento de Rua</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-lightbulb-line text-warning me-2"></i>
                                    <small>Iluminação Pública</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-road-map-line text-info me-2"></i>
                                    <small>Manutenção de Vias</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-broom-line text-success me-2"></i>
                                    <small>Limpeza Pública</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-shield-check-line text-danger me-2"></i>
                                    <small>Segurança Pública</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal de carregamento -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <h5 class="mb-2">Enviando solicitação...</h5>
                <p class="text-muted mb-0">Por favor, aguarde enquanto processamos sua solicitação e enviamos o email de confirmação.</p>
            </div>
        </div>
    </div>
</div>
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
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa
    const map = L.map('map').setView([-18.7167, -39.8667], 13); // Coordenadas de Guriri, São Mateus - ES
    
    // Adicionar camada de tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    let marker = null;
    
    // Adicionar marcador ao clicar no mapa
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Remover marcador anterior se existir
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Adicionar novo marcador
        marker = L.marker([lat, lng]).addTo(map);
        
        // Atualizar campos ocultos
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Buscar endereço reverso (opcional)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    const endereco = data.display_name.split(',')[0];
                    if (endereco && !document.getElementById('endereco').value) {
                        document.getElementById('endereco').value = endereco;
                    }
                }
            })
            .catch(error => console.log('Erro ao buscar endereço:', error));
    });
    
    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    cepInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
    
    // Validação do formulário
    const form = document.getElementById('solicitacao-form');
    form.addEventListener('submit', function(e) {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        
        if (!latitude || !longitude) {
            e.preventDefault();
            alert('Por favor, clique no mapa para marcar a localização da solicitação.');
            return false;
        }
        
        // Mostrar carregamento
        mostrarCarregamento();
    });
    
    // Função para mostrar carregamento
    function mostrarCarregamento() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        // Desabilitar botão e mostrar spinner
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        
        // Mostrar modal de carregamento
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
    }
    
    // Função para esconder carregamento (caso necessário)
    function esconderCarregamento() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        // Reabilitar botão e esconder spinner
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        
        // Esconder modal de carregamento
        const loadingModal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
        if (loadingModal) {
            loadingModal.hide();
        }
    }
});
</script>
@endpush
