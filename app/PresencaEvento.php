<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresencaEvento extends Model
{
    protected $table = 'presencas_eventos';

    protected $fillable = [
        'evento_id',
        'user_id',
        'nome',
        'cpf',
        'email',
        'telefone',
        'data_presenca',
        'ip_address',
        'observacoes'
    ];

    protected $casts = [
        'data_presenca' => 'datetime'
    ];

    /**
     * Relacionamento com evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($presenca) {
            if (!$presenca->data_presenca) {
                $presenca->data_presenca = now();
            }
            if (!$presenca->ip_address) {
                $presenca->ip_address = request()->ip();
            }
        });
    }

    /**
     * Scope para presenças de um evento
     */
    public function scopeDoEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    /**
     * Scope para presenças de um usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}