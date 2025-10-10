<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ContaPagar extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contas_pagar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
        'observacoes',
        'valor',
        'categoria',
        'categoria_id',
        'fornecedor',
        'fornecedor_id',
        'cnpj_fornecedor',
        'telefone_fornecedor',
        'email_fornecedor',
        'numero_nota_fiscal',
        'serie_nota_fiscal',
        'data_emissao_nota',
        'chave_acesso_nfe',
        'data_vencimento',
        'data_pagamento',
        'data_competencia',
        'status',
        'forma_pagamento',
        'valor_pago',
        'juros',
        'multa',
        'desconto',
        'parcelado',
        'numero_parcela',
        'total_parcelas',
        'conta_pagar_origem_id',
        'evento_id',
        'comprovante_pagamento',
        'arquivo_nota_fiscal',
        'cadastrado_por',
        'pago_por',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'juros' => 'decimal:2',
        'multa' => 'decimal:2',
        'desconto' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'data_emissao_nota' => 'date',
        'data_competencia' => 'date',
        'parcelado' => 'boolean',
    ];

    /**
     * Relacionamento com Fornecedor
     */
    public function fornecedorRelacao()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    /**
     * Relacionamento com Categoria
     */
    public function categoriaRelacao()
    {
        return $this->belongsTo(CategoriaConta::class, 'categoria_id');
    }

    /**
     * Relacionamento com Admin (cadastrado por)
     */
    public function cadastradoPor()
    {
        return $this->belongsTo(Admin::class, 'cadastrado_por');
    }

    /**
     * Relacionamento com Admin (pago por)
     */
    public function pagoPor()
    {
        return $this->belongsTo(Admin::class, 'pago_por');
    }

    /**
     * Relacionamento com conta original (se for parcela)
     */
    public function contaOrigem()
    {
        return $this->belongsTo(ContaPagar::class, 'conta_pagar_origem_id');
    }

    /**
     * Relacionamento com parcelas (se for conta original)
     */
    public function parcelas()
    {
        return $this->hasMany(ContaPagar::class, 'conta_pagar_origem_id');
    }

    /**
     * Relacionamento com Evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    /**
     * Verifica se a conta está vencida
     */
    public function isVencida()
    {
        return $this->status === 'pendente' && $this->data_vencimento < Carbon::now();
    }

    /**
     * Verifica se a conta está paga
     */
    public function isPaga()
    {
        return $this->status === 'pago';
    }

    /**
     * Verifica se a conta está pendente
     */
    public function isPendente()
    {
        return $this->status === 'pendente';
    }

    /**
     * Retorna o valor total (com juros e multa, menos desconto)
     */
    public function getValorTotalAttribute()
    {
        return $this->valor + $this->juros + $this->multa - $this->desconto;
    }

    /**
     * Retorna o valor formatado
     */
    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    /**
     * Retorna o valor total formatado
     */
    public function getValorTotalFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    }

    /**
     * Retorna a data de vencimento formatada
     */
    public function getDataVencimentoFormatadaAttribute()
    {
        return $this->data_vencimento ? $this->data_vencimento->format('d/m/Y') : '-';
    }

    /**
     * Retorna a data de pagamento formatada
     */
    public function getDataPagamentoFormatadaAttribute()
    {
        return $this->data_pagamento ? $this->data_pagamento->format('d/m/Y') : '-';
    }

    /**
     * Retorna os dias de atraso
     */
    public function getDiasAtrasoAttribute()
    {
        if ($this->isVencida()) {
            return Carbon::now()->diffInDays($this->data_vencimento);
        }
        return 0;
    }

    /**
     * Retorna o status em português
     */
    public function getStatusTextoAttribute()
    {
        $status = [
            'pendente' => 'Pendente',
            'pago' => 'Pago',
            'vencido' => 'Vencido',
            'cancelado' => 'Cancelado',
        ];

        return $status[$this->status] ?? $this->status;
    }

    /**
     * Retorna a forma de pagamento em português
     */
    public function getFormaPagamentoTextoAttribute()
    {
        $formas = [
            'dinheiro' => 'Dinheiro',
            'pix' => 'PIX',
            'transferencia' => 'Transferência',
            'boleto' => 'Boleto',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'cheque' => 'Cheque',
        ];

        return $formas[$this->forma_pagamento] ?? $this->forma_pagamento;
    }

    /**
     * Retorna a classe CSS do badge de status
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pendente' => 'bg-warning',
            'pago' => 'bg-success',
            'vencido' => 'bg-danger',
            'cancelado' => 'bg-secondary',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    /**
     * Scope para contas pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope para contas pagas
     */
    public function scopePagas($query)
    {
        return $query->where('status', 'pago');
    }

    /**
     * Scope para contas vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->where('status', 'pendente')
                     ->where('data_vencimento', '<', Carbon::now());
    }

    /**
     * Scope para contas do mês
     */
    public function scopeDoMes($query, $mes = null, $ano = null)
    {
        $mes = $mes ?? Carbon::now()->month;
        $ano = $ano ?? Carbon::now()->year;

        return $query->whereMonth('data_vencimento', $mes)
                     ->whereYear('data_vencimento', $ano);
    }
}

