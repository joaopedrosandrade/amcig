@extends('layouts.associado')

@section('title', 'Parcerias - ' . ucfirst($categoria) . ' - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Parcerias - {{ ucfirst($categoria) }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parcerias.index') }}">Parcerias</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($categoria) }}</li>
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
                        <p class="text-muted mb-0">{{ $parcerias->count() }} parcerias encontradas nesta categoria</p>
                    </div>
                    <div>
                        <a href="{{ route('parcerias.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Ver Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Parcerias da Categoria -->
    @if($parcerias->count() > 0)
        <div class="row">
            @foreach($parcerias as $parceria)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 {{ $parceria->destaque ? 'border-warning' : '' }}">
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
                                <div class="d-flex align-items-center gap-1 mb-2">
                                    <span class="badge bg-primary">{{ ucfirst($parceria->categoria) }}</span>
                                    @if($parceria->destaque)
                                        <span class="badge bg-warning text-dark">
                                            <i class="ri-star-fill me-1"></i>Destaque
                                        </span>
                                    @endif
                                </div>
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
                                @if($parceria->valor_minimo_pedido)
                                    <small class="text-muted">Mín: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}</small>
                                @endif
                            </div>
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
    @else
        <div class="text-center py-5">
            <i class="ri-percent-line text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">Nenhuma parceria encontrada</h5>
            <p class="text-muted">Não há parcerias disponíveis nesta categoria no momento.</p>
            <a href="{{ route('parcerias.index') }}" class="btn btn-primary">
                <i class="ri-arrow-left-line me-1"></i>Ver Todas as Parcerias
            </a>
        </div>
    @endif
</main>
@endsection
