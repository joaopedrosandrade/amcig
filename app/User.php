<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'matricula', 'cpf', 'data_nascimento', 'telefone', 'email', 'password',
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

    /**
     * Gera a matrícula automaticamente baseada no CPF, ID e data de cadastro
     *
     * @return string
     */
    public function gerarMatricula()
    {
        // Remove caracteres não numéricos do CPF
        $cpfNumeros = preg_replace('/[^0-9]/', '', $this->cpf);
        
        // Pega os 2 últimos dígitos do CPF
        $ultimosDigitosCPF = substr($cpfNumeros, -2);
        
        // Formata o ID com 5 dígitos (ex: 00001, 00002, 00010, 99999)
        $idFormatado = str_pad($this->id, 5, '0', STR_PAD_LEFT);
        
        // Pega o mês e ano de cadastro (MMYY) - usa a data atual se created_at for null
        $dataCadastro = $this->created_at ?? now();
        $mesAno = $dataCadastro->format('my');
        
        // Monta a matrícula: últimos 2 dígitos CPF + ID (5 dígitos) + MMYY
        $matricula = $ultimosDigitosCPF . $idFormatado . $mesAno;
        
        return $matricula;
    }

    /**
     * Boot do modelo para gerar matrícula automaticamente
     */
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($user) {
            // Gera a matrícula após o usuário ser criado (quando o ID já existe)
            $matricula = $user->gerarMatricula();
            
            // Atualiza apenas o campo matricula sem disparar eventos
            DB::table('users')
                ->where('id', $user->id)
                ->update(['matricula' => $matricula]);
        });
    }
}
