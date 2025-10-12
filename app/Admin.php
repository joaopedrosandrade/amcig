<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    
    // =====>>> novo
    protected $guard = 'admin';
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'is_superadmin', 'updated_by', 'last_login_at'
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
        'status' => 'boolean',
        'is_superadmin' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relacionamento com permissões
     */
    public function permissions()
    {
        return $this->hasMany(AdminPermission::class);
    }

    /**
     * Relacionamento com admin que atualizou este registro
     */
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Relacionamento com admins atualizados por este admin
     */
    public function updatedAdmins()
    {
        return $this->hasMany(Admin::class, 'updated_by');
    }

    /**
     * Verifica se o admin é ativo
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Verifica se o admin é superadmin
     */
    public function isSuperAdmin()
    {
        return $this->is_superadmin;
    }

    /**
     * Verifica se tem permissão para um menu específico
     */
    public function hasPermission($menuKey, $action = 'view')
    {
        // Superadmin tem acesso total
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Busca a permissão específica
        $permission = $this->permissions()->where('menu_key', $menuKey)->first();
        
        if (!$permission) {
            return false;
        }

        return $permission->hasPermission($action);
    }

    /**
     * Obtém todas as permissões organizadas por menu
     */
    public function getPermissionsByMenu()
    {
        $permissions = [];
        $menus = AdminPermission::getAvailableMenus();
        
        foreach ($menus as $key => $name) {
            $permission = $this->permissions()->where('menu_key', $key)->first();
            $permissions[$key] = [
                'name' => $name,
                'can_view' => $permission ? $permission->can_view : false,
                'can_create' => $permission ? $permission->can_create : false,
                'can_update' => $permission ? $permission->can_update : false,
                'can_delete' => $permission ? $permission->can_delete : false,
            ];
        }
        
        return $permissions;
    }

    /**
     * Sincroniza as permissões do admin
     */
    public function syncPermissions($permissions, $updatedBy = null)
    {
        $menus = AdminPermission::getAvailableMenus();
        
        foreach ($menus as $menuKey => $menuName) {
            if (isset($permissions[$menuKey])) {
                $menuPermissions = $permissions[$menuKey];
                
                $this->permissions()->updateOrCreate(
                    ['menu_key' => $menuKey],
                    [
                        'can_view' => $menuPermissions['can_view'] ?? false,
                        'can_create' => $menuPermissions['can_create'] ?? false,
                        'can_update' => $menuPermissions['can_update'] ?? false,
                        'can_delete' => $menuPermissions['can_delete'] ?? false,
                        'updated_by' => $updatedBy,
                    ]
                );
            }
        }
    }
}
