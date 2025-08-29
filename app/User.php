<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cpf', 'data_nascimento', 'telefone', 'email', 'password',
        'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
        'tipo_associado', 'nome_comercio', 'endereco_comercio', 'ramo_atividade',
        'status', 'data_aprovacao'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'data_aprovacao' => 'datetime',
    ];

    /**
     * Verifica se o usuário é um associado aprovado
     *
     * @return bool
     */
    public function isAssociadoAprovado()
    {
        return $this->status === 'aprovado';
    }

    /**
     * Verifica se o usuário é um comerciante
     *
     * @return bool
     */
    public function isComerciante()
    {
        return $this->tipo_associado === 'comerciante';
    }

    /**
     * Verifica se o usuário é um morador
     *
     * @return bool
     */
    public function isMorador()
    {
        return $this->tipo_associado === 'morador';
    }
}
