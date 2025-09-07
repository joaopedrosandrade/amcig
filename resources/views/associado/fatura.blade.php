<div class="row">
    <div class="col-md-6">
        <h6 class="fw-semibold mb-3">Informações da Fatura</h6>
        <table class="table table-sm">
            <tr>
                <td class="fw-semibold">ID da Fatura:</td>
                <td>#{{ $invoice->id }}</td>
            </tr>
            <tr>
                <td class="fw-semibold">Valor:</td>
                <td>{{ $invoice->formatted_value }}</td>
            </tr>
            <tr>
                <td class="fw-semibold">Vencimento:</td>
                <td class="{{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                    {{ $invoice->formatted_due_date }}
                </td>
            </tr>
            <tr>
                <td class="fw-semibold">Status:</td>
                <td>
                    <span class="badge bg-{{ $invoice->isOverdue() ? 'danger' : ($invoice->isPaid() ? 'success' : 'warning') }}">
                        {{ $invoice->formatted_status }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="fw-semibold">Tipo de Cobrança:</td>
                <td>{{ ucfirst($invoice->billing_type) }}</td>
            </tr>
            @if($invoice->payment_date)
                <tr>
                    <td class="fw-semibold">Data do Pagamento:</td>
                    <td>{{ $invoice->formatted_payment_date }}</td>
                </tr>
            @endif
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="fw-semibold mb-3">Informações de Pagamento</h6>
        
        @if($invoice->isPaid())
            <div class="alert alert-success">
                <i class="ri-checkbox-circle-line me-2"></i>
                Esta fatura foi paga com sucesso!
            </div>
        @elseif($invoice->isOverdue())
            <div class="alert alert-danger">
                <i class="ri-error-warning-line me-2"></i>
                Esta fatura está vencida. Entre em contato conosco para regularizar.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="ri-time-line me-2"></i>
                Esta fatura está pendente de pagamento.
            </div>
        @endif

        @if($invoice->billing_type === 'PIX' && $invoice->pix_qr_code)
            <div class="text-center">
                <h6 class="fw-semibold mb-3">Pagamento via PIX</h6>
                <div class="mb-3">
                    <img src="data:image/png;base64,{{ $invoice->pix_qr_code }}" alt="QR Code PIX" class="img-fluid" style="max-width: 200px;">
                </div>
                @if($invoice->pix_copy_paste)
                    <div class="mb-3">
                        <label class="form-label">Chave PIX (Copiar e Colar):</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $invoice->pix_copy_paste }}" readonly id="pixKey">
                            <button class="btn btn-outline-secondary" type="button" onclick="copiarPix()">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($invoice->invoice_url)
            <div class="text-center mt-3">
                <a href="{{ $invoice->invoice_url }}" target="_blank" class="btn btn-primary">
                    <i class="ri-file-download-line me-2"></i>Baixar Boleto
                </a>
            </div>
        @endif
    </div>
</div>

@if($invoice->description)
    <div class="row mt-3">
        <div class="col-12">
            <h6 class="fw-semibold mb-2">Descrição</h6>
            <p class="text-muted">{{ $invoice->description }}</p>
        </div>
    </div>
@endif

<script>
function copiarPix() {
    const pixKey = document.getElementById('pixKey');
    pixKey.select();
    pixKey.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Mostrar feedback visual
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="ri-check-line"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>
