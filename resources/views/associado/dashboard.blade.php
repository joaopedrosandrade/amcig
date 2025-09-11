@extends('layouts.associado')

@section('title', 'Dashboard - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        

        <!-- Informações do Associado -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-3">
                                @if($user->photo)
                                    <img src="{{ $user->photo_url }}" alt="Avatar" class="avatar-lg rounded-circle">
                                @else
                                    <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="font-size: 24px; font-weight: bold;">
                                        {{ $user->getInitials() }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            
                            </div>
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <p class="text-muted mb-0">Tipo</p>
                                    <h6 class="mb-1">{{ ucfirst($user->tipo_associado) }}</h6>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-0">Matrícula</p>
                                    <h6 class="mb-1">{{ $user->matricula }}</h6>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-0">Telefone</p>
                                    <h6 class="mb-1">{{ $user->telefone }}</h6>
                                </div>
                            </div>
                            
                            @if($user->status === 'aprovado')
                                <div class="border-top pt-3 mt-3">
                                    <div class="text-center">
                                        <h6 class="text-primary mb-2">Carteirinha de Associado</h6>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if(isset($user->matricula) && !empty($user->matricula))
                                                <a href="{{ route('associado.carteirinha', $user->matricula) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Visualizar Carteirinha">
                                                    <i class="ri-eye-line me-1"></i>
                                                    Visualizar
                                                </a>
                                                <a href="{{ route('associado.carteirinha.print', $user->matricula) }}" target="_blank" class="btn btn-outline-success btn-sm" title="Imprimir Carteirinha">
                                                    <i class="ri-printer-line me-1"></i>
                                                    Imprimir
                                                </a>
                                            @else
                                                <span class="text-muted">Matrícula não disponível</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informações de Endereço</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Endereço:</strong><br>
                                    {{ $user->logradouro }}, {{ $user->numero }}
                                    @if($user->complemento)
                                        - {{ $user->complemento }}
                                    @endif
                                </p>
                                <p class="mb-2">
                                    <strong>Bairro:</strong> {{ $user->bairro }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Cidade:</strong> {{ $user->cidade }}/{{ $user->uf }}
                                </p>
                                <p class="mb-2">
                                    <strong>CEP:</strong> {{ $user->cep }}
                                </p>
                            </div>
                        </div>
                        
                        @if($user->isComerciante())
                            <div class="border-top pt-3 mt-3">
                                <h6 class="text-primary mb-2">Informações do Comércio</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nome:</strong> {{ $user->nome_comercio }}</p>
                                        <p class="mb-1"><strong>Ramo:</strong> {{ ucfirst($user->ramo_atividade) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Endereço:</strong> {{ $user->endereco_comercio }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Acesso Rápido -->
        @if($user->status === 'aprovado')
            <div class="row">
               

                <div class="col-xl-12 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Mensalidades</p>
                                    @if($user->getStatusPagamento() === 'inadimplente')
                                        <h4 class="mb-0 text-danger">Inadimplente</h4>
                                        <small class="text-danger">{{ $user->getDiasAtraso() }} dias em atraso</small>
                                    @elseif($user->getStatusPagamento() === 'em_dia')
                                        <h4 class="mb-0 text-success">Em dia</h4>
                                    @else
                                        <h4 class="mb-0 text-muted">Sem assinatura</h4>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-{{ $user->getStatusPagamento() === 'inadimplente' ? 'danger' : ($user->getStatusPagamento() === 'em_dia' ? 'success' : 'secondary') }} d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-bank-card-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('associado.pagamentos') }}" class="btn btn-{{ $user->getStatusPagamento() === 'inadimplente' ? 'danger' : 'info' }} btn-sm">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>

              
            </div>

     
        @endif
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection
