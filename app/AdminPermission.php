<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'menu_key',
        'can_view',
        'can_create',
        'can_update',
        'can_delete',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    /**
     * Relacionamento com Admin (proprietário da permissão)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Relacionamento com Admin (quem atualizou a permissão)
     */
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Constantes para menu_key
     */
    const MENU_ASSOCIADOS = 'associados';
    const MENU_EVENTOS = 'eventos';
    const MENU_PARCERIAS = 'parcerias';
    const MENU_FINANCEIRO = 'financeiro';
    const MENU_SOLICITACOES = 'solicitacoes';
    const MENU_CONFIG_SISTEMA = 'config_sistema';
    const MENU_CONTAS_BANCARIAS = 'contas_bancarias';

    /**
     * Lista de menus disponíveis
     */
    public static function getAvailableMenus()
    {
        return [
            self::MENU_ASSOCIADOS => 'Associados',
            self::MENU_EVENTOS => 'Eventos',
            self::MENU_PARCERIAS => 'Parcerias',
            self::MENU_FINANCEIRO => 'Financeiro',
            self::MENU_SOLICITACOES => 'Solicitações',
            self::MENU_CONFIG_SISTEMA => 'Configurações do Sistema',
            self::MENU_CONTAS_BANCARIAS => 'Contas Bancárias',
        ];
    }

    /**
     * Verifica se tem permissão para uma ação específica
     */
    public function hasPermission($action)
    {
        switch ($action) {
            case 'view':
                return $this->can_view;
            case 'create':
                return $this->can_create;
            case 'update':
                return $this->can_update;
            case 'delete':
                return $this->can_delete;
            default:
                return false;
        }
    }
}
