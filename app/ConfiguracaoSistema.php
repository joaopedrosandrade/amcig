<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoSistema extends Model
{
    protected $table = 'configuracoes_sistema';
    
    protected $fillable = [
        'chave', 'nome', 'descricao', 'categoria', 
        'valor_boolean', 'valor_string', 'valor_integer', 'valor_text', 'ativo'
    ];
    
    protected $casts = [
        'valor_boolean' => 'boolean',
        'ativo' => 'boolean',
    ];

    /**
     * Busca uma configuração por chave
     */
    public static function get($chave, $default = null)
    {
        $config = static::where('chave', $chave)->where('ativo', true)->first();
        
        if (!$config) {
            return $default;
        }
        
        // Retorna o valor apropriado baseado no tipo
        if (!is_null($config->valor_boolean)) {
            return $config->valor_boolean;
        }
        if (!is_null($config->valor_string)) {
            return $config->valor_string;
        }
        if (!is_null($config->valor_integer)) {
            return $config->valor_integer;
        }
        if (!is_null($config->valor_text)) {
            return $config->valor_text;
        }
        
        return $default;
    }

    /**
     * Define uma configuração
     */
    public static function set($chave, $valor, $nome = null, $descricao = null, $categoria = 'geral')
    {
        $config = static::where('chave', $chave)->first();
        
        if (!$config) {
            $config = new static();
            $config->chave = $chave;
            $config->nome = $nome ?: ucfirst(str_replace('_', ' ', $chave));
            $config->descricao = $descricao;
            $config->categoria = $categoria;
        }
        
        // Limpa todos os valores
        $config->valor_boolean = null;
        $config->valor_string = null;
        $config->valor_integer = null;
        $config->valor_text = null;
        
        // Define o valor apropriado
        if (is_bool($valor)) {
            $config->valor_boolean = $valor;
        } elseif (is_int($valor)) {
            $config->valor_integer = $valor;
        } elseif (is_string($valor) && strlen($valor) <= 255) {
            $config->valor_string = $valor;
        } else {
            $config->valor_text = $valor;
        }
        
        $config->save();
        return $config;
    }

    /**
     * Verifica se uma funcionalidade está habilitada
     */
    public static function isEnabled($chave)
    {
        return static::get($chave, false);
    }

    /**
     * Desabilita uma funcionalidade
     */
    public static function disable($chave)
    {
        return static::set($chave, false);
    }

    /**
     * Habilita uma funcionalidade
     */
    public static function enable($chave)
    {
        return static::set($chave, true);
    }
}
