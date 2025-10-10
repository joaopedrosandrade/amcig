<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Evento extends Model
{
    protected $table = 'eventos_sistema';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_evento',
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
        'data_evento' => 'date',
        'lista_presenca_ativa' => 'boolean',
        'quorum_minimo' => 'integer'
    ];

    /**
     * Relacionamento com presenças
     */
    public function presencas()
    {
        return $this->hasMany(PresencaEvento::class, 'evento_id');
    }

    /**
     * Relacionamento com contas a pagar (despesas do evento)
     */
    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class, 'evento_id');
    }

    /**
     * Retorna o total de despesas do evento
     */
    public function getTotalDespesasAttribute()
    {
        return $this->contasPagar()->sum('valor');
    }

    /**
     * Retorna o total de despesas pagas do evento
     */
    public function getTotalDespesasPagasAttribute()
    {
        return $this->contasPagar()->where('status', 'pago')->sum('valor');
    }

    /**
     * Retorna o total de despesas pendentes do evento
     */
    public function getTotalDespesasPendentesAttribute()
    {
        return $this->contasPagar()->where('status', 'pendente')->sum('valor');
    }

    /**
     * Retorna as despesas formatadas
     */
    public function getTotalDespesasFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->total_despesas, 2, ',', '.');
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
     * Scope para eventos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('lista_presenca_ativa', true);
    }

    /**
     * Scope para eventos por status
     */
    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para eventos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}