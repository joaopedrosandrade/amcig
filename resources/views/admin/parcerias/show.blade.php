@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Detalhes da Parceria</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.parcerias.index') }}">Parcerias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $parceria->nome_empresa }}</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.parcerias.edit', $parceria->id) }}" class="btn btn-primary btn-sm">
                                <i class="ri-edit-line me-1"></i>Editar
                            </a>
                            <a href="{{ route('admin.parcerias.index') }}" class="btn btn-secondary btn-sm">
                                <i class="ri-arrow-left-line me-1"></i>Voltar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Dados da Empresa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ri-building-line me-2"></i>Dados da Empresa
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nome da Empresa</label>
                                    <p class="form-control-plaintext">{{ $parceria->nome_empresa }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Categoria</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-primary">{{ $parceria->categoria }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($parceria->descricao)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descrição</label>
                            <p class="form-control-plaintext">{{ $parceria->descricao }}</p>
                        </div>
                        @endif

                        <div class="row">
                            @if($parceria->telefone)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Telefone</label>
                                    <p class="form-control-plaintext">
                                        <i class="ri-phone-line me-1"></i>{{ $parceria->telefone }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if($parceria->email)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">E-mail</label>
                                    <p class="form-control-plaintext">
                                        <i class="ri-mail-line me-1"></i>
                                        <a href="mailto:{{ $parceria->email }}">{{ $parceria->email }}</a>
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if($parceria->website)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Website</label>
                                    <p class="form-control-plaintext">
                                        <i class="ri-global-line me-1"></i>
                                        <a href="{{ $parceria->website }}" target="_blank">{{ $parceria->website }}</a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($parceria->endereco)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Endereço</label>
                            <p class="form-control-plaintext">
                                <i class="ri-map-pin-line me-1"></i>{{ $parceria->endereco }}
                            </p>
                        </div>
                        @endif

                        <!-- Configurações de Desconto -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ri-percent-line me-2"></i>Configurações de Desconto
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tipo de Desconto</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-success">{{ $parceria->tipo_desconto }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Valor do Desconto</label>
                                    <p class="form-control-plaintext fs-5 fw-bold text-success">
                                        {{ $parceria->desconto_formatado }}
                                    </p>
                                </div>
                            </div>
                            @if($parceria->valor_minimo_pedido)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Valor Mínimo do Pedido</label>
                                    <p class="form-control-plaintext">
                                        R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($parceria->condicoes_desconto)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Condições Especiais</label>
                            <div class="alert alert-info">
                                <i class="ri-information-line me-1"></i>{{ $parceria->condicoes_desconto }}
                            </div>
                        </div>
                        @endif

                        <!-- Configurações Adicionais -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ri-settings-3-line me-2"></i>Configurações Adicionais
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ordem de Exibição</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-secondary">{{ $parceria->ordem }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <p class="form-control-plaintext">
                                        @if($parceria->ativo)
                                            <span class="badge bg-success">Ativa</span>
                                        @else
                                            <span class="badge bg-danger">Inativa</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Destaque</label>
                                    <p class="form-control-plaintext">
                                        @if($parceria->destaque)
                                            <span class="badge bg-warning">Em Destaque</span>
                                        @else
                                            <span class="badge bg-light text-dark">Normal</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Sistema -->
                        <div class="row mt-4 pt-3 border-top">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Criado em:</label>
                                    <p class="form-control-plaintext text-muted">{{ $parceria->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Última atualização:</label>
                                    <p class="form-control-plaintext text-muted">{{ $parceria->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
