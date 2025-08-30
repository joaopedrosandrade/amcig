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
                                <img src="{{asset('assets/images/avatar/avatar-1.jpg')}}" alt="Avatar" class="avatar-lg rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            
                            </div>
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Tipo</p>
                                    <h6 class="mb-1">{{ ucfirst($user->tipo_associado) }}</h6>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Telefone</p>
                                    <h6 class="mb-1">{{ $user->telefone }}</h6>
                                </div>
                            </div>
                            
                            @if($user->status === 'aprovado')
                                <div class="border-top pt-3 mt-3">
                                    <div class="text-center">
                                        <h6 class="text-primary mb-2">Carteirinha de Associado</h6>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#" class="btn btn-outline-primary btn-sm" title="Visualizar Carteirinha">
                                                <i class="ri-eye-line me-1"></i>
                                                Visualizar
                                            </a>
                                            <a href="#" class="btn btn-outline-success btn-sm" title="Baixar Carteirinha">
                                                <i class="ri-download-line me-1"></i>
                                                Baixar
                                            </a>
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
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Próximas Reuniões</p>
                                    <h4 class="mb-0">2</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-calendar-event-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-primary btn-sm">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Documentos</p>
                                    <h4 class="mb-0">15</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-file-text-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-success btn-sm">Acessar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Mensalidades</p>
                                    <h4 class="mb-0">Em dia</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-info d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-bank-card-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-info btn-sm">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Notificações</p>
                                    <h4 class="mb-0">3</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-notification-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-warning btn-sm">Ver Todas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Próximas Assembleias</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">Assembleia Geral Ordinária</h6>
                                <p class="text-muted mb-0">28 de Agosto, 2024 - 19:00</p>
                                <small class="text-muted">Local: Sede da AMCIG</small>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="mb-1">Reunião do Comitê</h6>
                                <p class="text-muted mb-0">30 de Agosto, 2024 - 15:00</p>
                                <small class="text-muted">Local: Online</small>
                            </div>
                            
                            <div class="text-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">Ver Todas as Reuniões</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Documentos Recentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">Estatuto Atualizado</h6>
                                <p class="text-muted mb-0">Atualizado em 26/08/2024</p>
                                <small class="text-muted">Versão 2.1</small>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="mb-1">Regimento Interno</h6>
                                <p class="text-muted mb-0">Atualizado em 25/08/2024</p>
                                <small class="text-muted">Versão 1.5</small>
                            </div>
                            
                            <div class="text-center">
                                <a href="#" class="btn btn-outline-info btn-sm">Ver Todos os Documentos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection
