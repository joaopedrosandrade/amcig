@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Detalhes da Conta a Pagar</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Financeiro</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fluxo-caixa.contas-pagar') }}">Contas a Pagar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Mensagens -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informações Principais -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Informações da Conta</h5>
                        <span class="badge {{ $conta->status_badge_class }} fs-6">
                            {{ $conta->status_texto }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3"><i class="ri-information-line me-2"></i>Dados Básicos</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Descrição</label>
                                <p class="fw-semibold">{{ $conta->descricao }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="text-muted mb-1">Categoria</label>
                                <p class="fw-semibold">{{ $conta->categoria }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="text-muted mb-1">Valor Original</label>
                                <p class="fw-semibold fs-5">{{ $conta->valor_formatado }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Data de Vencimento</label>
                                <p class="fw-semibold">{{ $conta->data_vencimento_formatada }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Data de Competência</label>
                                <p class="fw-semibold">{{ $conta->data_competencia ? $conta->data_competencia->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        @if($conta->evento)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="text-muted mb-1">Evento Vinculado</label>
                                <p class="fw-semibold">
                                    <a href="{{ route('admin.eventos.show', $conta->evento->id) }}" class="text-info text-decoration-none" title="Ver detalhes do evento">
                                        <i class="ri-calendar-event-line"></i> {{ $conta->evento->titulo }}
                                        <i class="ri-external-link-line ms-1"></i>
                                    </a>
                                    <br><small class="text-muted">{{ $conta->evento->data_evento ? $conta->evento->data_evento->format('d/m/Y') : '' }}</small>
                                </p>
                            </div>
                        </div>
                        @endif

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3"><i class="ri-building-line me-2"></i>Fornecedor</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Nome</label>
                                <p class="fw-semibold">{{ $conta->fornecedor }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">CPF/CNPJ</label>
                                <p class="fw-semibold">{{ $conta->cnpj_fornecedor ?? '-' }}</p>
                            </div>
                            @if($conta->telefone_fornecedor || $conta->email_fornecedor)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Telefone</label>
                                <p class="fw-semibold">{{ $conta->telefone_fornecedor ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">E-mail</label>
                                <p class="fw-semibold">{{ $conta->email_fornecedor ?? '-' }}</p>
                            </div>
                            @endif
                        </div>

                        @if($conta->numero_nota_fiscal)
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3"><i class="ri-file-text-line me-2"></i>Nota Fiscal</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted mb-1">Número</label>
                                <p class="fw-semibold">{{ $conta->numero_nota_fiscal }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted mb-1">Série</label>
                                <p class="fw-semibold">{{ $conta->serie_nota_fiscal ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted mb-1">Data de Emissão</label>
                                <p class="fw-semibold">{{ $conta->data_emissao_nota ? $conta->data_emissao_nota->format('d/m/Y') : '-' }}</p>
                            </div>
                            @if($conta->chave_acesso_nfe)
                            <div class="col-md-12 mb-3">
                                <label class="text-muted mb-1">Chave de Acesso NFe</label>
                                <p class="fw-semibold font-monospace small">{{ $conta->chave_acesso_nfe }}</p>
                            </div>
                            @endif
                            @if($conta->arquivo_nota_fiscal)
                            <div class="col-md-12">
                                <a href="{{ asset('storage/' . $conta->arquivo_nota_fiscal) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-file-download-line me-1"></i> Ver Nota Fiscal
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($conta->observacoes)
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-muted mb-1">Observações</label>
                                <p class="fw-semibold">{{ $conta->observacoes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações de Pagamento -->
            <div class="col-md-4">
                <!-- Status e Valores -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Resumo Financeiro</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted mb-1">Status</label>
                            <p>
                                <span class="badge {{ $conta->status_badge_class }} fs-6">
                                    {{ $conta->status_texto }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Valor Original</label>
                            <p class="fw-semibold fs-5">{{ $conta->valor_formatado }}</p>
                        </div>

                        @if($conta->isPaga())
                            @if($conta->desconto > 0)
                            <div class="mb-2">
                                <label class="text-muted mb-1">Desconto</label>
                                <p class="text-success">- R$ {{ number_format($conta->desconto, 2, ',', '.') }}</p>
                            </div>
                            @endif
                            
                            @if($conta->juros > 0)
                            <div class="mb-2">
                                <label class="text-muted mb-1">Juros</label>
                                <p class="text-danger">+ R$ {{ number_format($conta->juros, 2, ',', '.') }}</p>
                            </div>
                            @endif
                            
                            @if($conta->multa > 0)
                            <div class="mb-2">
                                <label class="text-muted mb-1">Multa</label>
                                <p class="text-danger">+ R$ {{ number_format($conta->multa, 2, ',', '.') }}</p>
                            </div>
                            @endif
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label class="text-muted mb-1">Valor Total Pago</label>
                                <p class="fw-bold fs-4 text-success">{{ $conta->valor_total_formatado }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($conta->isPaga())
                <!-- Informações do Pagamento -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Dados do Pagamento</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted mb-1">Data do Pagamento</label>
                            <p class="fw-semibold">{{ $conta->data_pagamento_formatada }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Forma de Pagamento</label>
                            <p class="fw-semibold">{{ $conta->forma_pagamento_texto }}</p>
                        </div>

                        @if($conta->contaBancaria)
                        <div class="mb-3">
                            <label class="text-muted mb-1">Conta Bancária Debitada</label>
                            <p class="fw-semibold">
                                <i class="ri-bank-line text-primary"></i> {{ $conta->contaBancaria->nome }}<br>
                                <small class="text-muted">{{ $conta->contaBancaria->banco }}</small>
                            </p>
                        </div>
                        @endif
                        
                        @if($conta->pagoPor)
                        <div class="mb-3">
                            <label class="text-muted mb-1">Pago por</label>
                            <p class="fw-semibold">{{ $conta->pagoPor->name }}</p>
                        </div>
                        @endif

                        @if($conta->comprovante_pagamento)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $conta->comprovante_pagamento) }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                <i class="ri-file-download-line me-1"></i> Ver Comprovante
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Informações do Sistema -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informações do Sistema</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="text-muted mb-1">Cadastrado em</label>
                            <p class="small">{{ $conta->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($conta->cadastradoPor)
                        <div class="mb-2">
                            <label class="text-muted mb-1">Cadastrado por</label>
                            <p class="small">{{ $conta->cadastradoPor->name }}</p>
                        </div>
                        @endif
                        
                        @if($conta->updated_at != $conta->created_at)
                        <div class="mb-2">
                            <label class="text-muted mb-1">Última atualização</label>
                            <p class="small">{{ $conta->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card mt-3">
                    <div class="card-body">
                        <a href="{{ route('admin.fluxo-caixa.contas-pagar') }}" class="btn btn-secondary w-100 mb-2">
                            <i class="ri-arrow-left-line me-1"></i> Voltar para Listagem
                        </a>
                        
                        @if(!$conta->isPaga())
                        <a href="{{ route('admin.fluxo-caixa.contas-pagar.edit', $conta->id) }}" class="btn btn-primary w-100 mb-2">
                            <i class="ri-edit-line me-1"></i> Editar Conta
                        </a>
                        
                        <button type="button" class="btn btn-danger w-100" onclick="confirmarExclusao({{ $conta->id }})">
                            <i class="ri-delete-bin-line me-1"></i> Excluir Conta
                        </button>
                        
                        <form id="form-delete-{{ $conta->id }}" 
                              action="{{ route('admin.fluxo-caixa.contas-pagar.destroy', $conta->id) }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="ri-lock-line me-2"></i>
                            Esta conta já foi paga e não pode mais ser editada ou excluída.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarExclusao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-delete-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection

