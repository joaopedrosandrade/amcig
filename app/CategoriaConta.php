<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaConta extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categorias_contas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'tipo',
        'cor',
        'descricao',
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
        return $this->hasMany(ContaPagar::class, 'categoria_id');
    }

    /**
     * Scope para categorias ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para categorias de contas a pagar
     */
    public function scopePagar($query)
    {
        return $query->where('tipo', 'pagar');
    }

    /**
     * Scope para categorias de contas a receber
     */
    public function scopeReceber($query)
    {
        return $query->where('tipo', 'receber');
    }
}

