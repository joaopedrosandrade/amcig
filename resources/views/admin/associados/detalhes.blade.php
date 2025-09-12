@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Detalhes do Associado</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.associados.index') }}">Associados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $associado->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Header do Associado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center me-3">
                                        <i class="ri-user-line text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">{{ $associado->name }}</h4>
                                        <p class="text-muted mb-1">{{ $associado->email }}</p>
                                        <p class="text-muted mb-0">
                                            @switch($associado->tipo_associado)
                                                @case('morador')
                                                    <span class="badge bg-primary">Morador</span>
                                                    @break
                                                @case('comerciante')
                                                    <span class="badge bg-info">Comerciante</span>
                                                    @break
                                            @endswitch
                                            @switch($associado->status)
                                                @case('aprovado')
                                                    <span class="badge bg-success ms-2">Aprovado</span>
                                                    @break
                                                @case('pendente')
                                                    <span class="badge bg-warning ms-2">Pendente</span>
                                                    @break
                                            @case('rejeitado')
                                                <span class="badge bg-danger ms-2">Rejeitado</span>
                                                @break
                                            @case('desativado')
                                                <span class="badge bg-warning ms-2">Desativado</span>
                                                @break
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.associados.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>Voltar
                                </a>
                                @if($associado->status !== 'desativado' && $associado->status !== 'pendente')
                                    <button type="button" class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#desativarModal">
                                        <i class="ri-user-forbid-line me-1"></i>Desativar
                                    </button>
                                @endif
                                @if($associado->status == 'pendente')
                                    <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#aprovarModal">
                                        <i class="ri-check-line me-1"></i>Aprovar
                                    </button>
                                    <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejeitarModal">
                                        <i class="ri-close-line me-1"></i>Rejeitar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Total de Mensalidades</p>
                                <h4 class="mb-0">{{ $totalMensalidades }}</h4>
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
                                <p class="text-truncate font-size-14 mb-2">Mensalidades Pagas</p>
                                <h4 class="mb-0">{{ $mensalidadesPagas }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-check-line text-success" style="font-size: 1.5rem;"></i>
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
                                <p class="text-truncate font-size-14 mb-2">Pendentes</p>
                                <h4 class="mb-0">{{ $mensalidadesPendentes }}</h4>
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
                                <p class="text-truncate font-size-14 mb-2">Vencidas</p>
                                <h4 class="mb-0">{{ $mensalidadesVencidas }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-alarm-warning-line text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valores Financeiros -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Total Pago</p>
                                <h4 class="mb-0 text-success">R$ {{ number_format($valorTotalPago, 2, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-money-dollar-circle-line text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Valor Pendente</p>
                                <h4 class="mb-0 text-warning">R$ {{ number_format($valorPendente, 2, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-money-dollar-box-line text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Abas de Navegação -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="informacoes-tab" data-bs-toggle="tab" data-bs-target="#informacoes" type="button" role="tab" aria-controls="informacoes" aria-selected="true">
                                    <i class="ri-user-line me-1"></i>Informações Pessoais
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="endereco-tab" data-bs-toggle="tab" data-bs-target="#endereco" type="button" role="tab" aria-controls="endereco" aria-selected="false">
                                    <i class="ri-map-pin-line me-1"></i>Endereço
                                </button>
                            </li>
                            @if($associado->tipo_associado == 'comerciante')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="comercio-tab" data-bs-toggle="tab" data-bs-target="#comercio" type="button" role="tab" aria-controls="comercio" aria-selected="false">
                                        <i class="ri-store-line me-1"></i>Comércio
                                    </button>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="mensalidades-tab" data-bs-toggle="tab" data-bs-target="#mensalidades" type="button" role="tab" aria-controls="mensalidades" aria-selected="false">
                                    <i class="ri-file-list-line me-1"></i>Mensalidades
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="senha-tab" data-bs-toggle="tab" data-bs-target="#senha" type="button" role="tab" aria-controls="senha" aria-selected="false">
                                    <i class="ri-key-line me-1"></i>Alterar Senha
                                </button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Informações Pessoais -->
                            <div class="tab-pane fade show active" id="informacoes" role="tabpanel" aria-labelledby="informacoes-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Dados Básicos</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-semibold text-muted">Nome:</td>
                                                <td>{{ $associado->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Matrícula:</td>
                                                <td>{{ $associado->matricula ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Email:</td>
                                                <td>{{ $associado->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">CPF:</td>
                                                <td>{{ $associado->cpf ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Data de Nascimento:</td>
                                                <td>{{ $associado->data_nascimento ? $associado->data_nascimento->format('d/m/Y') : 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Sexo:</td>
                                                <td>
                                                    @if($associado->sexo)
                                                        @switch($associado->sexo)
                                                            @case('masculino')
                                                                Masculino
                                                                @break
                                                            @case('feminino')
                                                                Feminino
                                                                @break
                                                            @case('outro')
                                                                Outro
                                                                @break
                                                        @endswitch
                                                    @else
                                                        Não informado
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Informações Adicionais</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-semibold text-muted">Telefone:</td>
                                                <td>{{ $associado->telefone ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Tipo de Associado:</td>
                                                <td>
                                                    @switch($associado->tipo_associado)
                                                        @case('morador')
                                                            <span class="badge bg-primary">Morador</span>
                                                            @break
                                                        @case('comerciante')
                                                            <span class="badge bg-info">Comerciante</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Data de Cadastro:</td>
                                                <td>{{ $associado->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if($associado->data_aprovacao)
                                                <tr>
                                                    <td class="fw-semibold text-muted">Data de Aprovação:</td>
                                                    <td>{{ $associado->data_aprovacao->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endif
                                            @if($associado->motivo_rejeicao)
                                                <tr>
                                                    <td class="fw-semibold text-muted">Motivo da Rejeição:</td>
                                                    <td class="text-danger">{{ $associado->motivo_rejeicao }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Endereço Principal</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-semibold text-muted">CEP:</td>
                                                <td>{{ $associado->cep ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Logradouro:</td>
                                                <td>{{ $associado->logradouro ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Número:</td>
                                                <td>{{ $associado->numero ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Complemento:</td>
                                                <td>{{ $associado->complemento ?? 'Não informado' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Localização</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-semibold text-muted">Bairro:</td>
                                                <td>{{ $associado->bairro ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Cidade:</td>
                                                <td>{{ $associado->cidade ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">UF:</td>
                                                <td>{{ $associado->uf ?? 'Não informado' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Comércio (se aplicável) -->
                            @if($associado->tipo_associado == 'comerciante')
                                <div class="tab-pane fade" id="comercio" role="tabpanel" aria-labelledby="comercio-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Informações do Comércio</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-semibold text-muted">Nome do Comércio:</td>
                                                    <td>{{ $associado->nome_comercio ?? 'Não informado' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted">Ramo de Atividade:</td>
                                                    <td>{{ $associado->ramo_atividade ?? 'Não informado' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Endereço do Comércio</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-semibold text-muted">Endereço:</td>
                                                    <td>{{ $associado->endereco_comercio ?? 'Não informado' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Mensalidades -->
                            <div class="tab-pane fade" id="mensalidades" role="tabpanel" aria-labelledby="mensalidades-tab">
                                @if($mensalidades->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Data de Vencimento</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                    <th>Data de Pagamento</th>
                                                    <th>Método de Pagamento</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($mensalidades as $mensalidade)
                                                    <tr>
                                                        <td>{{ $mensalidade->id }}</td>
                                                        <td>{{ $mensalidade->due_date ? $mensalidade->due_date->format('d/m/Y') : '-' }}</td>
                                                        <td>R$ {{ number_format($mensalidade->value, 2, ',', '.') }}</td>
                                                        <td>
                                                            @switch($mensalidade->status)
                                                                @case('PENDING')
                                                                    <span class="badge bg-warning">Pendente</span>
                                                                    @break
                                                                @case('CONFIRMED')
                                                                    <span class="badge bg-success">Confirmado</span>
                                                                    @break
                                                                @case('RECEIVED')
                                                                    <span class="badge bg-success">Recebido</span>
                                                                    @break
                                                                @case('RECEIVED_IN_CASH')
                                                                    <span class="badge bg-success">Recebido em Dinheiro</span>
                                                                    @break
                                                                @case('OVERDUE')
                                                                    <span class="badge bg-danger">Vencido</span>
                                                                    @break
                                                                @case('REFUNDED')
                                                                    <span class="badge bg-secondary">Estornado</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-light text-dark">{{ $mensalidade->status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>{{ $mensalidade->payment_date ? $mensalidade->payment_date->format('d/m/Y') : '-' }}</td>
                                                        <td>
                                                            @if($mensalidade->billing_type)
                                                                @switch($mensalidade->billing_type)
                                                                    @case('CREDIT_CARD')
                                                                        Cartão de Crédito
                                                                        @break
                                                                    @case('BOLETO')
                                                                        Boleto
                                                                        @break
                                                                    @case('PIX')
                                                                        PIX
                                                                        @break
                                                                    @default
                                                                        {{ $mensalidade->billing_type }}
                                                                @endswitch
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="ri-file-list-line text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Nenhuma mensalidade encontrada para este associado.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Alterar Senha -->
                            <div class="tab-pane fade" id="senha" role="tabpanel" aria-labelledby="senha-tab">
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-4">
                                                    <i class="ri-key-line me-2"></i>Alterar Senha do Associado
                                                </h6>
                                                <form id="formResetSenha">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $associado->id }}">
                                                    
                                                    <div class="mb-3">
                                                        <label for="nova_senha" class="form-label">Nova Senha <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="nova_senha" name="nova_senha" placeholder="Digite a nova senha" required>
                                                        <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                                                        <div class="error-message" id="nova_senha-error"></div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="nova_senha_confirmation" class="form-label">Confirmar Nova Senha <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="nova_senha_confirmation" name="nova_senha_confirmation" placeholder="Digite novamente a nova senha" required>
                                                        <div class="error-message" id="nova_senha_confirmation-error"></div>
                                                    </div>
                                                    
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="button" class="btn btn-outline-secondary" id="cancelarResetSenha">
                                                            <i class="ri-close-line me-1"></i>Cancelar
                                                        </button>
                                                        <button type="submit" class="btn btn-primary" id="confirmarResetSenha">
                                                            <span class="btn-text">
                                                                <i class="ri-save-line me-1"></i>Alterar Senha
                                                            </span>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Container para notificações -->
<div id="notification-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
</div>

<!-- Modal para confirmar aprovação -->
<div class="modal fade" id="aprovarModal" tabindex="-1" aria-labelledby="aprovarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aprovarModalLabel">Confirmar Aprovação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja aprovar este associado?</p>
                <p class="text-muted">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarAprovacao">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarAprovacao">
                    <span class="btn-text">Confirmar Aprovação</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar rejeição -->
<div class="modal fade" id="rejeitarModal" tabindex="-1" aria-labelledby="rejeitarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejeitarModalLabel">Confirmar Rejeição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="motivoRejeicao" class="form-label">Motivo da rejeição <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="motivoRejeicao" rows="3" placeholder="Digite o motivo da rejeição..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarRejeicao">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarRejeicao">
                    <span class="btn-text">Confirmar Rejeição</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar desativação -->
<div class="modal fade" id="desativarModal" tabindex="-1" aria-labelledby="desativarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="desativarModalLabel">Confirmar Desativação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja desativar este associado?</p>
                <p class="text-muted">O associado não poderá mais acessar o sistema até que seja reativado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarDesativacao">Cancelar</button>
                <button type="button" class="btn btn-warning" id="confirmarDesativacao">
                    <span class="btn-text">Confirmar Desativação</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processando...
                    </span>
                </button>
            </div>
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
    
    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1rem;
    }
    
    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background: none;
    }
    
    .nav-tabs-custom .nav-link:hover {
        border-bottom-color: #dee2e6;
        color: #0d6efd;
    }
    
    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }
    
    .avatar-lg {
        width: 4rem;
        height: 4rem;
    }
    
    .avatar-sm {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    /* Estilos para notificações */
    #notification-container .toast {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    #notification-container .toast-body {
        font-weight: 500;
    }
    
    .toast.show {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
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

    // Função para mostrar notificações
    function mostrarNotificacao(mensagem, tipo = 'info') {
        const container = document.getElementById('notification-container');
        
        // Cores e ícones baseados no tipo
        let bgClass, iconClass;
        switch(tipo) {
            case 'success':
                bgClass = 'bg-success';
                iconClass = 'ri-check-line';
                break;
            case 'error':
                bgClass = 'bg-danger';
                iconClass = 'ri-error-warning-line';
                break;
            case 'warning':
                bgClass = 'bg-warning';
                iconClass = 'ri-alarm-warning-line';
                break;
            default:
                bgClass = 'bg-info';
                iconClass = 'ri-information-line';
        }
        
        // Criar elemento da notificação
        const notification = document.createElement('div');
        notification.className = `toast align-items-center text-white ${bgClass} border-0 show`;
        notification.setAttribute('role', 'alert');
        notification.style.minWidth = '300px';
        
        notification.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="${iconClass} me-2" style="font-size: 1.2rem;"></i>
                    ${mensagem}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Adicionar ao container
        container.appendChild(notification);
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }
        }, 5000);
        
        // Remover ao clicar no X
        notification.querySelector('.btn-close').addEventListener('click', () => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }
        });
    }

    // Confirmar aprovação
    $('#confirmarAprovacao').click(function() {
        // Mostrar estado de carregamento no botão
        mostrarCarregamento('confirmarAprovacao', true);
        
        // Mostrar modal de carregamento
        const loadingModal = mostrarModalCarregamento();
        
        // Fechar modal de confirmação
        $('#aprovarModal').modal('hide');
        
        $.ajax({
            url: '{{ route("admin.associados.aprovar") }}',
            type: 'POST',
            data: {
                id: {{ $associado->id }},
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Mostrar mensagem de sucesso
                    setTimeout(function() {
                        esconderModalCarregamento(loadingModal);
                        // Recarrega a página para atualizar os dados
                        location.reload();
                    }, 1000);
                } else {
                    esconderModalCarregamento(loadingModal);
                    mostrarCarregamento('confirmarAprovacao', false);
                    alert('Erro: ' + response.message);
                }
            },
            error: function() {
                esconderModalCarregamento(loadingModal);
                mostrarCarregamento('confirmarAprovacao', false);
                alert('Erro ao aprovar associado.');
            }
        });
    });

    // Confirmar rejeição
    $('#confirmarRejeicao').click(function() {
        const motivo = $('#motivoRejeicao').val().trim();
        
        if (!motivo) {
            alert('Por favor, digite o motivo da rejeição.');
            return;
        }
        
        // Mostrar estado de carregamento no botão
        mostrarCarregamento('confirmarRejeicao', true);
        
        // Mostrar modal de carregamento
        const loadingModal = mostrarModalCarregamento();
        
        // Fechar modal de confirmação
        $('#rejeitarModal').modal('hide');
        
        $.ajax({
            url: '{{ route("admin.associados.rejeitar") }}',
            type: 'POST',
            data: {
                id: {{ $associado->id }},
                motivo: motivo,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Mostrar mensagem de sucesso
                    setTimeout(function() {
                        esconderModalCarregamento(loadingModal);
                        // Recarrega a página para atualizar os dados
                        location.reload();
                    }, 1000);
                } else {
                    esconderModalCarregamento(loadingModal);
                    mostrarCarregamento('confirmarRejeicao', false);
                    alert('Erro: ' + response.message);
                }
            },
            error: function() {
                esconderModalCarregamento(loadingModal);
                mostrarCarregamento('confirmarRejeicao', false);
                alert('Erro ao rejeitar associado.');
            }
        });
    });

    // Confirmar desativação
    $('#confirmarDesativacao').click(function() {
        // Mostrar estado de carregamento no botão
        mostrarCarregamento('confirmarDesativacao', true);
        
        // Mostrar modal de carregamento
        const loadingModal = mostrarModalCarregamento();
        
        // Fechar modal de confirmação
        $('#desativarModal').modal('hide');
        
        $.ajax({
            url: '{{ route("admin.associados.desativar") }}',
            type: 'POST',
            data: {
                id: {{ $associado->id }},
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Mostrar mensagem de sucesso
                    setTimeout(function() {
                        esconderModalCarregamento(loadingModal);
                        mostrarNotificacao('Associado desativado com sucesso!', 'success');
                        // Recarrega a página para atualizar os dados
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }, 1000);
                } else {
                    esconderModalCarregamento(loadingModal);
                    mostrarCarregamento('confirmarDesativacao', false);
                    mostrarNotificacao('Erro: ' + response.message, 'error');
                }
            },
            error: function() {
                esconderModalCarregamento(loadingModal);
                mostrarCarregamento('confirmarDesativacao', false);
                mostrarNotificacao('Erro ao desativar associado.', 'error');
            }
        });
    });

    // Resetar botões quando modais são fechados
    $('#aprovarModal').on('hidden.bs.modal', function () {
        mostrarCarregamento('confirmarAprovacao', false);
    });

    $('#rejeitarModal').on('hidden.bs.modal', function () {
        mostrarCarregamento('confirmarRejeicao', false);
        $('#motivoRejeicao').val('');
    });

    $('#desativarModal').on('hidden.bs.modal', function () {
        mostrarCarregamento('confirmarDesativacao', false);
    });

    // Reset de senha
    $('#formResetSenha').on('submit', function(e) {
        e.preventDefault();
        
        // Limpar mensagens de erro anteriores
        $('.error-message').text('');
        
        // Validar senhas
        const senha = $('#nova_senha').val();
        const confirmacao = $('#nova_senha_confirmation').val();
        
        if (senha.length < 6) {
            $('#nova_senha-error').text('A senha deve ter pelo menos 6 caracteres.');
            return;
        }
        
        if (senha !== confirmacao) {
            $('#nova_senha_confirmation-error').text('As senhas não coincidem.');
            return;
        }
        
        // Mostrar estado de carregamento
        mostrarCarregamento('confirmarResetSenha', true);
        
        // Enviar formulário
        $.ajax({
            url: '{{ route("admin.associados.reset-password") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                mostrarCarregamento('confirmarResetSenha', false);
                
                if (response.success) {
                    // Limpar formulário
                    $('#formResetSenha')[0].reset();
                    
                    // Mostrar notificação de sucesso
                    mostrarNotificacao('Senha alterada com sucesso!', 'success');
                } else {
                    // Mostrar erros de validação
                    if (response.errors) {
                        for (let field in response.errors) {
                            $('#' + field + '-error').text(response.errors[field][0]);
                        }
                    } else {
                        mostrarNotificacao('Erro: ' + response.message, 'error');
                    }
                }
            },
            error: function(xhr) {
                mostrarCarregamento('confirmarResetSenha', false);
                
                if (xhr.status === 422) {
                    // Erros de validação
                    const errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('#' + field + '-error').text(errors[field][0]);
                    }
                } else {
                    mostrarNotificacao('Erro ao alterar senha. Tente novamente.', 'error');
                }
            }
        });
    });

    // Cancelar reset de senha
    $('#cancelarResetSenha').click(function() {
        $('#formResetSenha')[0].reset();
        $('.error-message').text('');
    });
});
</script>
@endpush