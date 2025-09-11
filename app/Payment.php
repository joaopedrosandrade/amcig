<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'asaas_payment_id',
        'value',
        'payment_date',
        'status',
        'payment_method',
        'description',
        'asaas_data'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'payment_date' => 'date',
        'asaas_data' => 'array'
    ];

    /**
     * Relacionamento com Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se o pagamento foi confirmado
     */
    public function isConfirmed(): bool
    {
        return in_array($this->status, ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']);
    }

    /**
     * Verifica se o pagamento está pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    /**
     * Verifica se o pagamento foi estornado
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
     * Obtém a data de pagamento formatada
     */
    public function getFormattedPaymentDateAttribute(): string
    {
        return $this->payment_date->format('d/m/Y');
    }

    /**
     * Obtém o status formatado em português
     */
    public function getFormattedStatusAttribute(): string
    {
        $statusMap = [
            'PENDING' => 'Pendente',
            'CONFIRMED' => 'Confirmado',
            'RECEIVED' => 'Recebido',
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
