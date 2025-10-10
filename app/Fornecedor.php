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
        'tipo_pessoa',
        'cpf',
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
     * Retorna o nome formatado com CPF/CNPJ se houver
     */
    public function getNomeCompletoAttribute()
    {
        $documento = $this->tipo_pessoa === 'fisica' ? $this->cpf : $this->cnpj;
        
        if ($documento) {
            return $this->nome . ' - ' . $documento;
        }
        return $this->nome;
    }

    /**
     * Verifica se é pessoa física
     */
    public function isPessoaFisica()
    {
        return $this->tipo_pessoa === 'fisica';
    }

    /**
     * Verifica se é pessoa jurídica
     */
    public function isPessoaJuridica()
    {
        return $this->tipo_pessoa === 'juridica';
    }
}

