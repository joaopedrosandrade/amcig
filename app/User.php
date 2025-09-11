<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'matricula', 'cpf', 'data_nascimento', 'telefone', 'email', 'password', 'photo',
        'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
        'tipo_associado', 'nome_comercio', 'endereco_comercio', 'ramo_atividade',
        'status', 'data_aprovacao', 'motivo_rejeicao'
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
     * Relacionamento com Subscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Relacionamento com Invoices
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Relacionamento com Payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relacionamento com Solicitações
     */
    public function solicitacoes(): HasMany
    {
        return $this->hasMany(Solicitacao::class);
    }

    /**
     * Obtém a assinatura ativa do usuário
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()->where('status', 'ACTIVE')->first();
    }

    /**
     * Verifica se o usuário tem assinatura ativa
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->where('status', 'ACTIVE')->exists();
    }

    /**
     * Obtém o valor da mensalidade baseado no tipo de associado
     */
    public function getMonthlyValue(): float
    {
        return $this->tipo_associado === 'comerciante' ? 15.00 : 10.00;
    }

    /**
     * Verificar se o associado está inadimplente
     */
    public function isInadimplente(): bool
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        // Buscar faturas em atraso (vencidas e não pagas)
        $invoicesOverdue = $this->invoices()
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->count();

        return $invoicesOverdue > 0;
    }

    /**
     * Obter faturas em atraso
     */
    public function getFaturasEmAtraso(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->invoices()
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Obter status de pagamento do associado
     */
    public function getStatusPagamento(): string
    {
        if (!$this->hasActiveSubscription()) {
            return 'sem_assinatura';
        }

        if ($this->isInadimplente()) {
            return 'inadimplente';
        }

        return 'em_dia';
    }

    /**
     * Obter dias de atraso da primeira fatura em atraso
     */
    public function getDiasAtraso(): int
    {
        $primeiraFaturaAtraso = $this->invoices()
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->orderBy('due_date', 'asc')
            ->first();

        if (!$primeiraFaturaAtraso) {
            return 0;
        }

        return now()->diffInDays($primeiraFaturaAtraso->due_date);
    }

    /**
     * Obter URL da foto do usuário ou avatar padrão
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        
        // Avatar padrão baseado nas iniciais do nome
        return $this->getDefaultAvatarUrl();
    }
    
    /**
     * Obter avatar padrão baseado nas iniciais
     */
    public function getDefaultAvatarUrl(): string
    {
        $initials = $this->getInitials();
        $color = $this->getAvatarColor();
        
        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=ffffff&size=200&bold=true";
    }
    
    /**
     * Obter iniciais do nome
     */
    public function getInitials(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
    
    /**
     * Obter cor do avatar baseada no ID
     */
    public function getAvatarColor(): string
    {
        $colors = [
            '6366f1', '8b5cf6', 'ec4899', 'ef4444', 'f97316',
            'eab308', '22c55e', '10b981', '06b6d4', '3b82f6'
        ];
        
        return $colors[$this->id % count($colors)];
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
