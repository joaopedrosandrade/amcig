@extends('layouts.associado')

@section('title', 'Minhas Mensalidades - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Minhas Mensalidades</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Minhas Mensalidades</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        <!-- Mensagens de Sucesso/Erro -->
        @if(session('success'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Resumo da Assinatura -->
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        @if($subscription)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="ri-subscription-line font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Minha Assinatura</h5>
                                    <p class="text-muted mb-0">Status: 
                                        <span class="badge bg-{{ $subscription->isActive() ? 'success' : ($subscription->isCancelled() ? 'danger' : 'warning') }}">
                                            {{ $subscription->isActive() ? 'Ativa' : ($subscription->isCancelled() ? 'Cancelada' : 'Inativa') }}
                                        </span>
                                    </p>
                                    @if($subscription->isActive())
                                        <p class="text-muted mb-0">Pagamentos: 
                                            @if($user->getStatusPagamento() === 'inadimplente')
                                                <span class="badge bg-danger">Inadimplente</span>
                                                <small class="text-danger d-block">{{ $user->getDiasAtraso() }} dias em atraso</small>
                                            @else
                                                <span class="badge bg-success">Em dia</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="border-top pt-3">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <p class="text-muted mb-0">Valor</p>
                                        <h6 class="mb-1">{{ $subscription->formatted_value }}</h6>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-0">Próximo Vencimento</p>
                                        <h6 class="mb-1">{{ $subscription->formatted_next_due_date }}</h6>
                                    </div>
                                </div>
                                
                                <div class="row text-center mt-3">
                                    <div class="col-12">
                                        <p class="text-muted mb-0">Tipo de Pagamento</p>
                                        <h6 class="mb-1">{{ ucfirst($subscription->billing_type) }}</h6>
                                    </div>
                                </div>
                                
                                @if($subscription->isActive())
                                    <div class="border-top pt-3 mt-3">
                                        <a href="{{ route('associado.cancelar-view') }}" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="ri-close-line me-1"></i>Cancelar Assinatura
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <span class="avatar-title">
                                        <i class="ri-error-warning-line font-size-24"></i>
                                    </span>
                                </div>
                                <h6 class="text-muted mb-0">Nenhuma assinatura encontrada</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Faturas Pendentes -->
            <div class="col-xl-8 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-line me-2"></i>Faturas Pendentes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($invoices->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fatura</th>
                                            <th>Vencimento</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>#{{ $invoice->id }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $invoice->description }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="{{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                                                        {{ $invoice->formatted_due_date }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ $invoice->formatted_value }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $invoice->isOverdue() ? 'danger' : 'warning' }}">
                                                        {{ $invoice->formatted_status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($invoice->status === 'PENDING' || $invoice->status === 'OVERDUE')
                                                            <a href="{{ route('associado.pagar-fatura', $invoice->id) }}" class="btn btn-sm btn-success" title="Pagar">
                                                                <i class="ri-money-dollar-circle-line me-1"></i>Pagar
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('associado.atualizar-fatura', $invoice->id) }}" class="btn btn-sm btn-outline-primary" title="Atualizar Status">
                                                            <i class="ri-refresh-line me-1"></i>Atualizar Status
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <span class="avatar-title">
                                        <i class="ri-checkbox-circle-line font-size-24"></i>
                                    </span>
                                </div>
                                <h6 class="text-muted mb-0">Nenhuma fatura pendente</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->

<!-- Modal de Pagamento -->
<div class="modal fade" id="pagamentoModal" tabindex="-1" aria-labelledby="pagamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pagamentoModalLabel">Pagamento via PIX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="pagamentoContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="verificarPagamento">
                    <span class="btn-text">Verificar Pagamento</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Verificando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-dismiss alerts após 5 segundos
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection