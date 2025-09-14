<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parceria extends Model
{
    protected $table = 'parcerias';
    
    protected $fillable = [
        'nome_empresa', 'descricao', 'telefone', 'email', 'endereco', 'website', 'logo',
        'categoria', 'tipo_desconto', 'valor_desconto', 'valor_minimo_pedido', 
        'condicoes_desconto', 'ativo', 'destaque', 'ordem'
    ];
    
    protected $casts = [
        'valor_desconto' => 'decimal:2',
        'valor_minimo_pedido' => 'decimal:2',
        'ativo' => 'boolean',
        'destaque' => 'boolean',
        'ordem' => 'integer',
    ];

    /**
     * Scope para parcerias ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para parcerias em destaque
     */
    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    /**
     * Scope para ordenação
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('ordem')->orderBy('nome_empresa');
    }

    /**
     * Formata o desconto para exibição
     */
    public function getDescontoFormatadoAttribute()
    {
        switch ($this->tipo_desconto) {
            case 'percentual':
                return $this->valor_desconto . '%';
            case 'valor_fixo':
                return 'R$ ' . number_format($this->valor_desconto, 2, ',', '.');
            case 'desconto_especial':
                return $this->condicoes_desconto;
            default:
                return 'Desconto especial';
        }
    }

    /**
     * Formata as condições do desconto
     */
    public function getCondicoesFormatadasAttribute()
    {
        $condicoes = [];
        
        if ($this->valor_minimo_pedido) {
            $condicoes[] = 'Pedido mínimo: R$ ' . number_format($this->valor_minimo_pedido, 2, ',', '.');
        }
        
        if ($this->condicoes_desconto && $this->tipo_desconto !== 'desconto_especial') {
            $condicoes[] = $this->condicoes_desconto;
        }
        
        return $condicoes;
    }

    /**
     * Retorna a URL completa da logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/logos/' . $this->logo);
        }
        return asset('assets/images/default-company.png');
    }

    /**
     * Categorias disponíveis
     */
    public static function getCategorias()
    {
        return [
            'geral' => 'Geral',
            'alimentacao' => 'Alimentação',
            'saude' => 'Saúde',
            'beleza' => 'Beleza',
            'educacao' => 'Educação',
            'tecnologia' => 'Tecnologia',
            'automoveis' => 'Automóveis',
            'imoveis' => 'Imóveis',
            'servicos' => 'Serviços',
            'outros' => 'Outros'
        ];
    }

    /**
     * Tipos de desconto disponíveis
     */
    public static function getTiposDesconto()
    {
        return [
            'percentual' => 'Percentual (%)',
            'valor_fixo' => 'Valor Fixo (R$)',
            'desconto_especial' => 'Desconto Especial'
        ];
    }
}
