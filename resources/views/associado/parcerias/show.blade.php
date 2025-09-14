@extends('layouts.associado')

@section('title', $parceria->nome_empresa . ' - Parcerias AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">{{ $parceria->nome_empresa }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parcerias.index') }}">Parcerias</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $parceria->nome_empresa }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

    <div class="row">
        <!-- Conteúdo Principal -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <!-- Header da Parceria -->
                    <div class="d-flex align-items-start mb-4">
                        @if($parceria->logo)
                            <img src="{{ asset('storage/logos/' . $parceria->logo) }}" 
                                 alt="Logo {{ $parceria->nome_empresa }}" 
                                 class="rounded me-4" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-4 d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="ri-building-line text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h1 class="h3 mb-2">{{ $parceria->nome_empresa }}</h1>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary fs-6">{{ ucfirst($parceria->categoria) }}</span>
                                @if($parceria->destaque)
                                    <span class="badge bg-warning text-dark">
                                        <i class="ri-star-fill me-1"></i>Destaque
                                    </span>
                                @endif
                            </div>
                            <div class="text-success fs-4 fw-bold">{{ $parceria->desconto_formatado }}</div>
                            @if($parceria->valor_minimo_pedido)
                                <p class="text-muted mb-0">Mínimo: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Descrição -->
                    @if($parceria->descricao)
                    <div class="mb-4">
                        <h5>Sobre a Empresa</h5>
                        <p class="text-muted">{{ $parceria->descricao }}</p>
                    </div>
                    @endif

                    <!-- Condições do Desconto -->
                    @if($parceria->condicoes_desconto)
                    <div class="mb-4">
                        <h5>Condições Especiais</h5>
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>{{ $parceria->condicoes_desconto }}
                        </div>
                    </div>
                    @endif

                    <!-- Informações de Contato -->
                    <div class="mb-4">
                        <h5>Informações de Contato</h5>
                        <div class="row">
                            @if($parceria->telefone)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line text-primary me-2"></i>
                                    <div>
                                        <strong>Telefone:</strong><br>
                                        <a href="tel:{{ $parceria->telefone }}" class="text-decoration-none">{{ $parceria->telefone }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($parceria->email)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-mail-line text-primary me-2"></i>
                                    <div>
                                        <strong>E-mail:</strong><br>
                                        <a href="mailto:{{ $parceria->email }}" class="text-decoration-none">{{ $parceria->email }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($parceria->endereco)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-map-pin-line text-primary me-2"></i>
                                    <div>
                                        <strong>Endereço:</strong><br>
                                        <span class="text-muted">{{ $parceria->endereco }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($parceria->website)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-global-line text-primary me-2"></i>
                                    <div>
                                        <strong>Website:</strong><br>
                                        <a href="{{ $parceria->website }}" target="_blank" class="text-decoration-none">
                                            {{ $parceria->website }} <i class="ri-external-link-line ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="d-flex gap-2 flex-wrap">
                        @if($parceria->website)
                            <a href="{{ $parceria->website }}" target="_blank" class="btn btn-primary">
                                <i class="ri-external-link-line me-1"></i>Visitar Website
                            </a>
                        @endif
                        
                        @if($parceria->telefone)
                            <a href="tel:{{ $parceria->telefone }}" class="btn btn-outline-primary">
                                <i class="ri-phone-line me-1"></i>Ligar
                            </a>
                        @endif
                        
                        @if($parceria->email)
                            <a href="mailto:{{ $parceria->email }}" class="btn btn-outline-primary">
                                <i class="ri-mail-line me-1"></i>Enviar E-mail
                            </a>
                        @endif
                        
                        <a href="{{ route('parcerias.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Parcerias Relacionadas -->
            @if($parceriasRelacionadas->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ri-handshake-line me-2"></i>Outras Parcerias da Categoria
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($parceriasRelacionadas as $parceriaRelacionada)
                    <div class="d-flex align-items-center mb-3">
                        @if($parceriaRelacionada->logo)
                            <img src="{{ asset('storage/logos/' . $parceriaRelacionada->logo) }}" 
                                 alt="Logo {{ $parceriaRelacionada->nome_empresa }}" 
                                 class="rounded me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="ri-building-line text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="{{ route('parcerias.show', $parceriaRelacionada->id) }}" class="text-decoration-none">
                                    {{ $parceriaRelacionada->nome_empresa }}
                                </a>
                            </h6>
                            <div class="text-success fw-bold">{{ $parceriaRelacionada->desconto_formatado }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Informações do Desconto -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ri-percent-line me-2"></i>Como Usar o Desconto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="ri-checkbox-circle-line me-2"></i>
                        <strong>Você é um associado AMCIG!</strong><br>
                        <small>Apresente sua carteirinha de associado para garantir o desconto.</small>
                    </div>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Apresente sua carteirinha digital
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Informe que é associado AMCIG
                        </li>
                        @if($parceria->valor_minimo_pedido)
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Valor mínimo: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}
                        </li>
                        @endif
                        @if($parceria->condicoes_desconto)
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Verifique condições especiais
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
