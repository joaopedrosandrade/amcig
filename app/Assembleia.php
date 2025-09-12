<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assembleia extends Model
{
    protected $table = 'assembleias_sistema';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_assembleia',
        'hora_inicio',
        'hora_fim',
        'local',
        'tipo',
        'status',
        'link_presenca',
        'lista_presenca_ativa',
        'pauta',
        'observacoes',
        'quorum_minimo'
    ];

    protected $casts = [
        'data_assembleia' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fim' => 'datetime:H:i',
        'lista_presenca_ativa' => 'boolean',
        'quorum_minimo' => 'integer'
    ];

    /**
     * Relacionamento com presenças
     */
    public function presencas()
    {
        return $this->hasMany(Presenca::class, 'assembleia_id');
    }

    /**
     * Gera um link único para lista de presença
     */
    public function gerarLinkPresenca()
    {
        if (!$this->link_presenca) {
            $this->link_presenca = Str::random(32);
            $this->save();
        }
        return $this->link_presenca;
    }

    /**
     * Ativa a lista de presença
     */
    public function ativarListaPresenca()
    {
        $this->gerarLinkPresenca();
        $this->update(['lista_presenca_ativa' => true]);
        return $this;
    }

    /**
     * Desativa a lista de presença
     */
    public function desativarListaPresenca()
    {
        $this->update(['lista_presenca_ativa' => false]);
        return $this;
    }

    /**
     * Obtém o total de presenças
     */
    public function getTotalPresencasAttribute()
    {
        return $this->presencas()->count();
    }

    /**
     * Verifica se atingiu o quorum
     */
    public function atingiuQuorum()
    {
        if (!$this->quorum_minimo) {
            return false;
        }
        return $this->total_presencas >= $this->quorum_minimo;
    }

    /**
     * Scope para assembleias ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('lista_presenca_ativa', true);
    }

    /**
     * Scope para assembleias por status
     */
    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para assembleias por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
