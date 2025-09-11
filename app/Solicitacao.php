<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Solicitacao extends Model
{
    protected $table = 'solicitacoes';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'descricao',
        'endereco',
        'bairro',
        'cidade',
        'cep',
        'latitude',
        'longitude',
        'prioridade',
        'status',
        'observacoes_admin',
        'data_limite',
        'data_conclusao',
        'admin_responsavel'
    ];

    protected $casts = [
        'data_limite' => 'datetime',
        'data_conclusao' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com o usuário que fez a solicitação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o admin responsável
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_responsavel');
    }

    /**
     * Retorna o nome amigável do tipo
     */
    public function getTipoNomeAttribute(): string
    {
        $tipos = [
            'PATRULHAMENTO_RUA' => 'Patrulhamento de Rua',
            'ILUMINACAO_PUBLICA' => 'Iluminação Pública',
            'MANUTENCAO_VIAS' => 'Manutenção de Vias',
            'LIMPEZA_PUBLICA' => 'Limpeza Pública',
            'SEGURANCA_PUBLICA' => 'Segurança Pública',
            'TRANSPORTE_PUBLICO' => 'Transporte Público',
            'SAUDE_PUBLICA' => 'Saúde Pública',
            'EDUCACAO' => 'Educação',
            'MEIO_AMBIENTE' => 'Meio Ambiente',
            'OUTROS' => 'Outros'
        ];

        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Retorna o nome amigável do status
     */
    public function getStatusNomeAttribute(): string
    {
        $status = [
            'ABERTA' => 'Aberta',
            'EM_ANALISE' => 'Em Análise',
            'EM_ANDAMENTO' => 'Em Andamento',
            'CONCLUIDA' => 'Concluída',
            'CANCELADA' => 'Cancelada',
            'REJEITADA' => 'Rejeitada'
        ];

        return $status[$this->status] ?? $this->status;
    }

    /**
     * Retorna o nome amigável da prioridade
     */
    public function getPrioridadeNomeAttribute(): string
    {
        $prioridades = [
            'BAIXA' => 'Baixa',
            'MEDIA' => 'Média',
            'ALTA' => 'Alta',
            'URGENTE' => 'Urgente'
        ];

        return $prioridades[$this->prioridade] ?? $this->prioridade;
    }

    /**
     * Retorna a cor da badge do status
     */
    public function getStatusCorAttribute(): string
    {
        $cores = [
            'ABERTA' => 'primary',
            'EM_ANALISE' => 'warning',
            'EM_ANDAMENTO' => 'info',
            'CONCLUIDA' => 'success',
            'CANCELADA' => 'secondary',
            'REJEITADA' => 'danger'
        ];

        return $cores[$this->status] ?? 'secondary';
    }

    /**
     * Retorna a cor da badge da prioridade
     */
    public function getPrioridadeCorAttribute(): string
    {
        $cores = [
            'BAIXA' => 'success',
            'MEDIA' => 'primary',
            'ALTA' => 'warning',
            'URGENTE' => 'danger'
        ];

        return $cores[$this->prioridade] ?? 'secondary';
    }

    /**
     * Verifica se a solicitação está atrasada
     */
    public function getAtrasadaAttribute(): bool
    {
        if (!$this->data_limite || $this->status === 'CONCLUIDA') {
            return false;
        }

        return $this->data_limite->isPast();
    }

    /**
     * Retorna o tempo decorrido desde a criação
     */
    public function getTempoDecorridoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope para filtrar por status
     */
    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por prioridade
     */
    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    /**
     * Scope para solicitações atrasadas
     */
    public function scopeAtrasadas($query)
    {
        return $query->where('data_limite', '<', now())
                    ->whereNotIn('status', ['CONCLUIDA', 'CANCELADA']);
    }
}
