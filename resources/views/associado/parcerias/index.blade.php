@extends('layouts.associado')

@section('title', 'Parcerias - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Parcerias</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Parcerias</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Empresas parceiras com descontos exclusivos para associados</p>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Filtro por categoria -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ri-filter-line me-1"></i>Categorias
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('parcerias.index') }}">Todas as Categorias</a></li>
                                @foreach($parceriasPorCategoria as $categoria => $parcerias)
                                    <li><a class="dropdown-item" href="{{ route('parcerias.categoria', $categoria) }}">{{ ucfirst($categoria) }} ({{ $parcerias->count() }})</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Parcerias em Destaque -->
    @if($parceriasDestaque->count() > 0)
    <div class="mb-5">
        <h4 class="mb-3">
            <i class="ri-star-fill text-warning me-2"></i>Parcerias em Destaque
        </h4>
        <div class="row">
            @foreach($parceriasDestaque as $parceria)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            @if($parceria->logo)
                                <img src="{{ asset('storage/logos/' . $parceria->logo) }}" 
                                     alt="Logo {{ $parceria->nome_empresa }}" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="ri-building-line text-muted fs-4"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">{{ $parceria->nome_empresa }}</h5>
                                <span class="badge bg-warning text-dark">
                                    <i class="ri-star-fill me-1"></i>Destaque
                                </span>
                            </div>
                        </div>
                        
                        @if($parceria->descricao)
                            <p class="card-text text-muted small mb-3">
                                {{ \Illuminate\Support\Str::limit($parceria->descricao, 100) }}
                            </p>
                        @endif
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-success fs-5">{{ $parceria->desconto_formatado }}</span>
                                <span class="badge bg-primary">{{ ucfirst($parceria->categoria) }}</span>
                            </div>
                            @if($parceria->valor_minimo_pedido)
                                <small class="text-muted">Mín: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}</small>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('parcerias.show', $parceria->id) }}" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="ri-eye-line me-1"></i>Ver Detalhes
                            </a>
                            @if($parceria->website)
                                <a href="{{ $parceria->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-external-link-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Todas as Parcerias -->
    <div class="mb-4">
        <h4 class="mb-3">
            <i class="ri-percent-line text-primary me-2"></i>Todas as Parcerias
        </h4>
        
        @if($parceriasNormais->count() > 0)
            <div class="row">
                @foreach($parceriasNormais as $parceria)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($parceria->logo)
                                    <img src="{{ asset('storage/logos/' . $parceria->logo) }}" 
                                         alt="Logo {{ $parceria->nome_empresa }}" 
                                         class="rounded mb-2" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded mx-auto mb-2 d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="ri-building-line text-muted fs-2"></i>
                                    </div>
                                @endif
                                <h6 class="card-title mb-1">{{ $parceria->nome_empresa }}</h6>
                                <span class="badge bg-info">{{ ucfirst($parceria->categoria) }}</span>
                            </div>
                            
                            <div class="text-center mb-3">
                                <div class="fw-bold text-success fs-5">{{ $parceria->desconto_formatado }}</div>
                                @if($parceria->valor_minimo_pedido)
                                    <small class="text-muted">Mín: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}</small>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('parcerias.show', $parceria->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ri-eye-line me-1"></i>Ver Detalhes
                                </a>
                                @if($parceria->website)
                                    <a href="{{ $parceria->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="ri-external-link-line me-1"></i>Visitar Site
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="ri-percent-line text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Nenhuma parceria encontrada</h5>
                <p class="text-muted">Novas parcerias serão adicionadas em breve.</p>
            </div>
        @endif
    </div>


</main>
@endsection
