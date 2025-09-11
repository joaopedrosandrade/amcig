<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'subscription_id',
        'user_id',
        'asaas_payment_id',
        'value',
        'due_date',
        'payment_date',
        'status',
        'billing_type',
        'description',
        'invoice_url',
        'pix_qr_code',
        'pix_copy_paste',
        'asaas_data'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
        'asaas_data' => 'array'
    ];

    /**
     * Relacionamento com Subscription
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Verifica se a fatura está pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    /**
     * Verifica se a fatura foi paga
     */
    public function isPaid(): bool
    {
        return in_array($this->status, ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']);
    }

    /**
     * Verifica se a fatura está vencida
     */
    public function isOverdue(): bool
    {
        return $this->status === 'OVERDUE' || ($this->due_date < now() && $this->isPending());
    }

    /**
     * Verifica se a fatura foi estornada
     */
    public function isRefunded(): bool
    {
        return $this->status === 'REFUNDED';
    }

    /**
     * Obtém o valor formatado em reais
     */
    public function getFormattedValueAttribute(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Obtém a data de vencimento formatada
     */
    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date->format('d/m/Y');
    }

    /**
     * Obtém a data de pagamento formatada
     */
    public function getFormattedPaymentDateAttribute(): ?string
    {
        return $this->payment_date ? $this->payment_date->format('d/m/Y') : null;
    }

    /**
     * Obtém o status formatado em português
     */
    public function getFormattedStatusAttribute(): string
    {
        $statusMap = [
            'PENDING' => 'Pendente',
            'CONFIRMED' => 'Confirmado',
            'RECEIVED_IN_CASH' => 'Recebido em Dinheiro',
            'OVERDUE' => 'Vencido',
            'REFUNDED' => 'Estornado',
            'RECEIVED_WITH_OVERDUE' => 'Recebido com Atraso',
            'CHARGEBACK_REQUESTED' => 'Chargeback Solicitado',
            'CHARGEBACK_DISPUTE' => 'Chargeback em Disputa',
            'AWAITING_CHARGEBACK_REVERSAL' => 'Aguardando Reversão',
            'DUNNING_REQUESTED' => 'Cobrança Solicitada',
            'DUNNING_RECEIVED' => 'Cobrança Recebida',
            'AWAITING_RISK_ANALYSIS' => 'Aguardando Análise'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}
