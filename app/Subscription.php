<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'asaas_subscription_id',
        'asaas_customer_id',
        'value',
        'billing_type',
        'next_due_date',
        'status',
        'description',
        'asaas_data'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'next_due_date' => 'date',
        'asaas_data' => 'array'
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Invoices
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Verifica se a assinatura está ativa
     */
    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    /**
     * Verifica se a assinatura está suspensa
     */
    public function isSuspended(): bool
    {
        return $this->status === 'SUSPENDED';
    }

    /**
     * Verifica se a assinatura está cancelada
     */
    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }

    /**
     * Obtém o valor formatado em reais
     */
    public function getFormattedValueAttribute(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Obtém a próxima data de vencimento formatada
     */
    public function getFormattedNextDueDateAttribute(): string
    {
        return $this->next_due_date->format('d/m/Y');
    }
}
