@extends('layouts.associado')

@section('title', 'Minhas Mensalidades - AMCIG')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                        <li class="breadcrumb-item active">Minhas Mensalidades</li>
                    </ol>
                </div>
                <h4 class="page-title">Minhas Mensalidades</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Resumo da Assinatura -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-subscription-line me-2"></i>Minha Assinatura
                    </h5>
                </div>
                <div class="card-body">
                    @if($subscription)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $subscription->isActive() ? 'success' : ($subscription->isCancelled() ? 'danger' : 'warning') }}">
                                {{ $subscription->isActive() ? 'Ativa' : ($subscription->isCancelled() ? 'Cancelada' : 'Inativa') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Valor:</span>
                            <span class="fw-semibold">{{ $subscription->formatted_value }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Próximo Vencimento:</span>
                            <span class="fw-semibold">{{ $subscription->formatted_next_due_date }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Tipo:</span>
                            <span class="fw-semibold">{{ ucfirst($subscription->billing_type) }}</span>
                        </div>
                        
                        @if($subscription->isActive())
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="cancelarAssinatura()">
                                <i class="ri-close-line me-1"></i>Cancelar Assinatura
                            </button>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="ri-error-warning-line text-warning" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Nenhuma assinatura encontrada</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Faturas Pendentes -->
        <div class="col-lg-8">
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
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verFatura({{ $invoice->id }})">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="atualizarFatura({{ $invoice->id }})">
                                                        <i class="ri-refresh-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-checkbox-circle-line text-success" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Nenhuma fatura pendente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Pagamentos -->
    @if($payments->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-history-line me-2"></i>Histórico de Pagamentos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Valor</th>
                                        <th>Método</th>
                                        <th>Status</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->formatted_payment_date }}</td>
                                            <td>
                                                <span class="fw-semibold">{{ $payment->formatted_value }}</span>
                                            </td>
                                            <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $payment->formatted_status }}</span>
                                            </td>
                                            <td>{{ $payment->description ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal para exibir fatura -->
<div class="modal fade" id="faturaModal" tabindex="-1" aria-labelledby="faturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faturaModalLabel">Detalhes da Fatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="faturaContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação para cancelar assinatura -->
<div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="cancelarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelarModalLabel">Cancelar Assinatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar sua assinatura?</p>
                <p class="text-muted">Esta ação não pode ser desfeita e você não receberá mais cobranças automáticas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarCancelamento">
                    <span class="btn-text">Sim, Cancelar</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function verFatura(invoiceId) {
    $.ajax({
        url: '{{ route("associado.fatura") }}',
        method: 'GET',
        data: { id: invoiceId },
        success: function(response) {
            $('#faturaContent').html(response);
            $('#faturaModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire('Erro', 'Erro ao carregar fatura', 'error');
        }
    });
}

function atualizarFatura(invoiceId) {
    $.ajax({
        url: '{{ route("associado.atualizar") }}',
        method: 'POST',
        data: { 
            id: invoiceId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Sucesso', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erro', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Erro', 'Erro ao atualizar fatura', 'error');
        }
    });
}

function cancelarAssinatura() {
    $('#cancelarModal').modal('show');
}

$('#confirmarCancelamento').click(function() {
    mostrarCarregamento('confirmarCancelamento', true);
    
    $.ajax({
        url: '{{ route("associado.cancelar") }}',
        method: 'POST',
        data: { 
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Sucesso', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erro', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Erro', 'Erro ao cancelar assinatura', 'error');
        },
        complete: function() {
            mostrarCarregamento('confirmarCancelamento', false);
            $('#cancelarModal').modal('hide');
        }
    });
});

function mostrarCarregamento(btnId, isLoading) {
    const btn = $('#' + btnId);
    const btnText = btn.find('.btn-text');
    const btnLoading = btn.find('.btn-loading');
    
    if (isLoading) {
        btnText.addClass('d-none');
        btnLoading.removeClass('d-none');
        btn.prop('disabled', true);
    } else {
        btnText.removeClass('d-none');
        btnLoading.addClass('d-none');
        btn.prop('disabled', false);
    }
}
</script>
@endsection
