@if($invoice)
<div class="alert alert-info">
    <h6>Debug - Fatura encontrada:</h6>
    <p>ID: {{ $invoice->id }}</p>
    <p>Valor: {{ $invoice->formatted_value }}</p>
    <p>Status: {{ $invoice->status }}</p>
    <p>QR Code: {{ $invoice->pix_qr_code ? 'Disponível' : 'Não disponível' }}</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Detalhes da Fatura</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Fatura:</strong> #{{ $invoice->id }}<br>
                    <strong>Descrição:</strong> {{ $invoice->description }}<br>
                    <strong>Valor:</strong> <span class="text-success fw-bold">{{ $invoice->formatted_value }}</span><br>
                    <strong>Vencimento:</strong> {{ $invoice->formatted_due_date }}<br>
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $invoice->isOverdue() ? 'danger' : 'warning' }}">
                        {{ $invoice->formatted_status }}
                    </span>
                </div>
                
                @if($invoice->isOverdue())
                    <div class="alert alert-danger">
                        <i class="ri-alert-line me-1"></i>
                        Esta fatura está em atraso há {{ now()->diffInDays($invoice->due_date) }} dias.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Pagamento via PIX</h6>
            </div>
            <div class="card-body text-center">
                @if($invoice->pix_qr_code)
                    <div class="mb-3">
                        <img src="data:image/png;base64,{{ $invoice->pix_qr_code }}" 
                             alt="QR Code PIX" 
                             class="img-fluid" 
                             style="max-width: 200px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Chave PIX (Copiar e Colar):</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="pixKey" 
                                   value="{{ $invoice->pix_copy_paste }}" 
                                   readonly>
                            <button class="btn btn-outline-secondary" 
                                    type="button" 
                                    onclick="copiarPixKey()">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        <strong>Instruções:</strong><br>
                        1. Abra seu app de pagamentos<br>
                        2. Escaneie o QR Code ou cole a chave PIX<br>
                        3. Confirme o valor: <strong>{{ $invoice->formatted_value }}</strong><br>
                        4. Realize o pagamento<br>
                        5. Clique em "Verificar Pagamento" para confirmar
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="ri-error-warning-line me-1"></i>
                        QR Code PIX não disponível. Clique em "Atualizar Status" para buscar os dados atualizados.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copiarPixKey() {
    const pixKeyInput = document.getElementById('pixKey');
    pixKeyInput.select();
    pixKeyInput.setSelectionRange(0, 99999); // Para dispositivos móveis
    
    try {
        document.execCommand('copy');
        Swal.fire({
            title: 'Copiado!',
            text: 'Chave PIX copiada para a área de transferência',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    } catch (err) {
        Swal.fire('Erro', 'Não foi possível copiar a chave PIX', 'error');
    }
}
</script>
@else
<div class="alert alert-danger">
    <i class="ri-error-warning-line me-1"></i>
    Fatura não encontrada ou não pertence ao usuário.
</div>
@endif
