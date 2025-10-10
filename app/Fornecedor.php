<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fornecedores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'email',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'ativo',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com contas a pagar
     */
    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class, 'fornecedor_id');
    }

    /**
     * Scope para fornecedores ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Retorna o nome formatado com CNPJ se houver
     */
    public function getNomeCompletoAttribute()
    {
        if ($this->cnpj) {
            return $this->nome . ' - ' . $this->cnpj;
        }
        return $this->nome;
    }
}

