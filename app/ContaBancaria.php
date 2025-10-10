<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancaria extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contas_bancarias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'banco',
        'agencia',
        'numero_conta',
        'tipo_conta',
        'saldo_inicial',
        'saldo_atual',
        'titular',
        'cpf_cnpj_titular',
        'observacoes',
        'ativo',
        'principal',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'saldo_atual' => 'decimal:2',
        'ativo' => 'boolean',
        'principal' => 'boolean',
    ];

    /**
     * Relacionamento com contas a pagar
     */
    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class, 'conta_bancaria_id');
    }

    /**
     * Scope para contas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Retorna o nome completo da conta
     */
    public function getNomeCompletoAttribute()
    {
        $info = $this->nome;
        
        if ($this->agencia && $this->numero_conta) {
            $info .= ' - Ag: ' . $this->agencia . ' / CC: ' . $this->numero_conta;
        }
        
        if ($this->principal) {
            $info .= ' ⭐';
        }
        
        return $info;
    }

    /**
     * Retorna o saldo formatado
     */
    public function getSaldoFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->saldo_atual, 2, ',', '.');
    }

    /**
     * Retorna o tipo de conta em português
     */
    public function getTipoContaTextoAttribute()
    {
        $tipos = [
            'corrente' => 'Conta Corrente',
            'poupanca' => 'Poupança',
            'aplicacao' => 'Aplicação',
            'caixa' => 'Caixa',
        ];

        return $tipos[$this->tipo_conta] ?? $this->tipo_conta;
    }

    /**
     * Atualiza o saldo da conta
     */
    public function atualizarSaldo($valor, $tipo = 'debito')
    {
        if ($tipo === 'debito') {
            $this->saldo_atual -= $valor;
        } else {
            $this->saldo_atual += $valor;
        }
        
        $this->save();
    }
}

