@extends('layouts.associado')

@section('title', 'Pagamento - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Pagamento via PIX</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('associado.pagamentos') }}">Minhas Mensalidades</a></li>
                            <li class="breadcrumb-item active">Pagamento</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        @if($invoice)
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <!-- Detalhes da Fatura -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-list-line me-2"></i>Detalhes da Fatura
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                                <span class="avatar-title">
                                                    <i class="ri-file-text-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Fatura #{{ $invoice->id }}</h6>
                                            <p class="text-muted mb-0">{{ $invoice->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end">
                                        <h4 class="text-success mb-1">{{ $invoice->formatted_value }}</h4>
                                        <p class="text-muted mb-0">Vencimento: {{ $invoice->formatted_due_date }}</p>
                                        <span class="badge bg-{{ $invoice->isOverdue() ? 'danger' : 'warning' }} mt-2">
                                            {{ $invoice->formatted_status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($invoice->isOverdue())
                                <div class="alert alert-danger mt-3">
                                    <i class="ri-alert-line me-1"></i>
                                    <strong>Atenção:</strong> Esta fatura está em atraso há {{ now()->diffInDays($invoice->due_date) }} dias.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pagamento PIX -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-qr-code-line me-2"></i>Pagamento via PIX
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(in_array($invoice->status, ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']))
                                <!-- Pagamento Confirmado -->
                                <div class="text-center py-5">
                                    <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <span class="avatar-title">
                                            <i class="ri-check-line font-size-24"></i>
                                        </span>
                                    </div>
                                    <h5 class="text-success mb-3">Pagamento Confirmado!</h5>
                                    <p class="text-muted mb-4">Seu pagamento foi processado com sucesso.</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h6 class="mb-2">Valor Pago</h6>
                                                    <h4 class="text-success mb-0">R$ {{ number_format($invoice->value, 2, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h6 class="mb-2">Data do Pagamento</h6>
                                                    <h6 class="text-muted mb-0">{{ $invoice->payment_date ? $invoice->payment_date->format('d/m/Y H:i') : 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('associado.pagamentos') }}" class="btn btn-primary">
                                            <i class="ri-arrow-left-line me-2"></i>Voltar para Mensalidades
                                        </a>
                                    </div>
                                </div>
                            @elseif($invoice->pix_qr_code)
                                <div class="row">
                                    <!-- QR Code -->
                                    <div class="col-md-6 text-center">
                                        <div class="mb-4">
                                            <h6 class="mb-3">Escaneie o QR Code</h6>
                                            <div class="p-3 border rounded bg-light d-inline-block">
                                                <img src="data:image/png;base64,{{ $invoice->pix_qr_code }}" 
                                                     alt="QR Code PIX" 
                                                     class="img-fluid" 
                                                     style="max-width: 250px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Chave PIX -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <h6 class="mb-3">Ou copie a chave PIX</h6>
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control form-control-lg" 
                                                       id="pixKey" 
                                                       value="{{ $invoice->pix_copy_paste }}" 
                                                       readonly>
                                                <button class="btn btn-primary" 
                                                        type="button" 
                                                        onclick="copiarPixKey()">
                                                    <i class="ri-file-copy-line me-1"></i>Copiar
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Instruções -->
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="ri-information-line me-2"></i>Como pagar:
                                            </h6>
                                            <ol class="mb-0">
                                                <li>Abra seu app de pagamentos (PIX)</li>
                                                <li>Escaneie o QR Code ou cole a chave PIX</li>
                                                <li>Confirme o valor: <strong>{{ $invoice->formatted_value }}</strong></li>
                                                <li>Realize o pagamento</li>
                                                <li>Clique em "Verificar Pagamento" abaixo</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botões de Ação -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('associado.pagamentos') }}" class="btn btn-secondary">
                                                <i class="ri-arrow-left-line me-1"></i>Voltar
                                            </a>
                                            
                                            <div>
                                                <a href="{{ route('associado.atualizar-fatura-direta', $invoice->id) }}" class="btn btn-outline-info me-2">
                                                    <i class="ri-refresh-line me-1"></i>Atualizar Status
                                                </a>
                                                <button type="button" class="btn btn-warning me-2" onclick="buscarQrCodePix()">
                                                    <i class="ri-qr-code-line me-1"></i>Buscar QR Code PIX
                                                </button>
                                                <button type="button" class="btn btn-success" onclick="verificarPagamento()">
                                                    <i class="ri-check-line me-1"></i>Verificar Pagamento
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <span class="avatar-title">
                                            <i class="ri-time-line font-size-24"></i>
                                        </span>
                                    </div>
                                    <h6 class="text-muted mb-3">QR Code PIX sendo gerado</h6>
                                    <p class="text-muted mb-4">O Asaas está processando os dados do PIX. Isso pode levar alguns minutos. Tente novamente em breve.</p>
                                    
                                    <div class="alert alert-info mb-4">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>Dica:</strong> Você pode pagar diretamente no Asaas enquanto aguardamos o QR Code ser gerado.
                                    </div>
                                    
                                    <div class="alert alert-warning mb-4">
                                        <i class="ri-time-line me-2"></i>
                                        <strong>Verificação Automática:</strong> A página está verificando automaticamente se o pagamento foi confirmado. Você também pode clicar em "Verificar Pagamento" a qualquer momento.
                                    </div>
                                    
                                    <div class="mb-4">
                                        <button type="button" class="btn btn-warning me-2" onclick="buscarQrCodePix()">
                                            <i class="ri-qr-code-line me-1"></i>Buscar QR Code PIX
                                        </button>
                                        <a href="{{ route('associado.atualizar-fatura-direta', $invoice->id) }}" class="btn btn-outline-info">
                                            <i class="ri-refresh-line me-1"></i>Atualizar Status
                                        </a>
                                    </div>
                                    
                                    @if($invoice->invoice_url)
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Pagamento Direto no Asaas</h6>
                                                <p class="text-muted mb-3">Clique no botão abaixo para abrir a página de pagamento do Asaas:</p>
                                                <a href="{{ $invoice->invoice_url }}" target="_blank" class="btn btn-success btn-lg">
                                                    <i class="ri-external-link-line me-2"></i>Pagar no Asaas
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('associado.pagamentos') }}" class="btn btn-secondary">
                                            <i class="ri-arrow-left-line me-1"></i>Voltar
                                        </a>
                                        <a href="{{ route('associado.atualizar-fatura', $invoice->id) }}" class="btn btn-primary">
                                            <i class="ri-refresh-line me-1"></i>Tentar Novamente
                                        </a>
                                        @if($invoice->invoice_url)
                                            <a href="{{ $invoice->invoice_url }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="ri-external-link-line me-1"></i>Abrir no Asaas
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="avatar-sm rounded-circle bg-danger d-flex align-items-center justify-content-center mx-auto mb-3">
                                <span class="avatar-title">
                                    <i class="ri-error-warning-line font-size-24"></i>
                                </span>
                            </div>
                            <h6 class="text-muted mb-3">Fatura não encontrada</h6>
                            <p class="text-muted mb-4">A fatura solicitada não foi encontrada ou não pertence ao seu usuário.</p>
                            <a href="{{ route('associado.pagamentos') }}" class="btn btn-primary">
                                <i class="ri-arrow-left-line me-1"></i>Voltar para Minhas Mensalidades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection

@section('scripts')
<script>
function copiarPixKey() {
    const pixKeyInput = document.getElementById('pixKey');
    pixKeyInput.select();
    pixKeyInput.setSelectionRange(0, 99999); // Para dispositivos móveis
    
    try {
        document.execCommand('copy');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Copiado!',
                text: 'Chave PIX copiada para a área de transferência',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert('Chave PIX copiada para a área de transferência!');
        }
    } catch (err) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Erro', 'Não foi possível copiar a chave PIX', 'error');
        } else {
            alert('Erro: Não foi possível copiar a chave PIX');
        }
    }
}

function verificarPagamento() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="ri-loader-4-line me-1"></i>Verificando...';
    button.disabled = true;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Verificando pagamento...',
            text: 'Aguarde enquanto verificamos o status do pagamento.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    fetch('{{ route("associado.verificar-pagamento") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id: {{ $invoice->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.pago) {
                // Pagamento confirmado
                Swal.fire({
                    title: 'Pagamento Confirmado!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Recarregar a página para mostrar o status atualizado
                    location.reload();
                });
            } else {
                // Pagamento ainda não confirmado
                Swal.fire({
                    title: 'Pagamento Pendente',
                    text: data.message,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        } else {
            Swal.fire({
                title: 'Erro',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Erro ao verificar pagamento:', error);
        Swal.fire({
            title: 'Erro',
            text: 'Erro ao verificar pagamento. Tente novamente.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        // Restaurar botão
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Função para buscar QR Code PIX especificamente
function buscarQrCodePix() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="ri-loader-4-line me-1"></i>Buscando...';
    button.disabled = true;
    
    fetch('{{ route("associado.buscar-qr-code-pix") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id: {{ $invoice->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensagem de sucesso
            showAlert('success', data.message);
            
            // Recarregar a página para mostrar o QR Code
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Erro ao buscar QR Code PIX:', error);
        showAlert('error', 'Erro ao buscar QR Code PIX. Tente novamente.');
    })
    .finally(() => {
        // Restaurar botão
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Função para mostrar alertas
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Inserir alerta no topo da página
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Remover alerta após 5 segundos
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Auto-refresh para buscar QR Code e verificar pagamento
$(document).ready(function() {
    @if(!in_array($invoice->status, ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']))
        let refreshCount = 0;
        const maxRefreshAttempts = 20; // Máximo 20 tentativas (10 minutos)
        
        const autoRefresh = setInterval(function() {
            refreshCount++;
            
            if (refreshCount > maxRefreshAttempts) {
                clearInterval(autoRefresh);
                console.log('Parou de verificar pagamento após ' + maxRefreshAttempts + ' tentativas');
                return;
            }
            
            console.log('Tentativa ' + refreshCount + ' de verificar pagamento...');
            
            // Verificar se o pagamento foi confirmado
            fetch('{{ route("associado.verificar-pagamento") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: {{ $invoice->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.pago) {
                    console.log('Pagamento confirmado! Recarregando página...');
                    clearInterval(autoRefresh);
                    
                    // Mostrar notificação de sucesso
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Pagamento Confirmado!',
                            text: 'Seu pagamento foi processado com sucesso.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                } else if (data.success && !data.pago) {
                    console.log('Pagamento ainda pendente');
                    
                    // Se não tem QR Code, tentar buscar
                    @if(!$invoice->pix_qr_code)
                        fetch('{{ route("associado.atualizar-fatura-direta", $invoice->id) }}', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('QR Code obtido! Recarregando página...');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar QR Code:', error);
                        });
                    @endif
                }
            })
            .catch(error => {
                console.error('Erro ao verificar pagamento:', error);
            });
        }, 30000); // Verificar a cada 30 segundos
    @endif
});
</script>
@endsection