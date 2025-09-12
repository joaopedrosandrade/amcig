<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $table = 'presencas_sistema';

    protected $fillable = [
        'assembleia_id',
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
     * Relacionamento com assembleia
     */
    public function assembleia()
    {
        return $this->belongsTo(Assembleia::class, 'assembleia_id');
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
     * Scope para presenças de uma assembleia
     */
    public function scopeDaAssembleia($query, $assembleiaId)
    {
        return $query->where('assembleia_id', $assembleiaId);
    }

    /**
     * Scope para presenças de um usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
