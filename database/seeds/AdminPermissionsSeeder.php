<?php

use Illuminate\Database\Seeder;
use App\Admin;
use App\AdminPermission;
use Illuminate\Support\Facades\Hash;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar superadmin se não existir
        $superAdmin = Admin::updateOrCreate(
            ['email' => 'superadmin@amcig.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('superadmin123'),
                'status' => true,
                'is_superadmin' => true,
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Super Administrador criado: superadmin@amcig.com / superadmin123');
        }

        // Criar admin com permissões limitadas (exemplo)
        $limitedAdmin = Admin::updateOrCreate(
            ['email' => 'admin.limitado@amcig.com'],
            [
                'name' => 'Administrador Limitado',
                'password' => Hash::make('admin123'),
                'status' => true,
                'is_superadmin' => false,
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Administrador limitado criado: admin.limitado@amcig.com / admin123');
        }

        // Definir permissões para o admin limitado
        $permissions = [
            'associados' => [
                'can_view' => true,
                'can_create' => false,
                'can_update' => true,
                'can_delete' => false,
            ],
            'eventos' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => false,
            ],
            'parcerias' => [
                'can_view' => true,
                'can_create' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'financeiro' => [
                'can_view' => true,
                'can_create' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'solicitacoes' => [
                'can_view' => true,
                'can_create' => false,
                'can_update' => true,
                'can_delete' => false,
            ],
            'config_sistema' => [
                'can_view' => false,
                'can_create' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'contas_bancarias' => [
                'can_view' => false,
                'can_create' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
        ];

        // Aplicar permissões ao admin limitado
        foreach ($permissions as $menuKey => $permissionData) {
            AdminPermission::updateOrCreate(
                [
                    'admin_id' => $limitedAdmin->id,
                    'menu_key' => $menuKey,
                ],
                array_merge($permissionData, [
                    'updated_by' => $superAdmin->id,
                ])
            );
        }

        if (isset($this->command)) {
            $this->command->info('Permissões aplicadas ao administrador limitado');
        }

        // Criar admin com acesso total exceto configurações
        $fullAdmin = Admin::updateOrCreate(
            ['email' => 'admin.completo@amcig.com'],
            [
                'name' => 'Administrador Completo',
                'password' => Hash::make('admin123'),
                'status' => true,
                'is_superadmin' => false,
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Administrador completo criado: admin.completo@amcig.com / admin123');
        }

        // Definir permissões para o admin completo
        $fullPermissions = [
            'associados' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => true,
            ],
            'eventos' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => true,
            ],
            'parcerias' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => true,
            ],
            'financeiro' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => false,
            ],
            'solicitacoes' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => true,
            ],
            'config_sistema' => [
                'can_view' => false,
                'can_create' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'contas_bancarias' => [
                'can_view' => true,
                'can_create' => true,
                'can_update' => true,
                'can_delete' => false,
            ],
        ];

        // Aplicar permissões ao admin completo
        foreach ($fullPermissions as $menuKey => $permissionData) {
            AdminPermission::updateOrCreate(
                [
                    'admin_id' => $fullAdmin->id,
                    'menu_key' => $menuKey,
                ],
                array_merge($permissionData, [
                    'updated_by' => $superAdmin->id,
                ])
            );
        }

        if (isset($this->command)) {
            $this->command->info('Permissões aplicadas ao administrador completo');
            $this->command->info('Seeders de permissões concluídos com sucesso!');
        }
    }
}
