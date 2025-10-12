# Módulo de Usuários Administrativos

Este módulo implementa um sistema completo de controle de acesso para usuários administrativos do sistema AMCIG.

## Funcionalidades Implementadas

### ✅ CRUD de Administradores
- Listagem de usuários administrativos com filtros e paginação
- Criação de novos usuários com permissões
- Edição de usuários existentes (incluindo senha opcional)
- Visualização detalhada de usuários
- Exclusão/desativação de usuários
- Controle de status (ativo/inativo)

### ✅ Sistema de Permissões
- Permissões granulares por módulo e ação
- 7 módulos disponíveis: Associados, Eventos, Parcerias, Financeiro, Solicitações, Configurações do Sistema, Contas Bancárias
- 4 ações por módulo: Visualizar, Criar, Editar, Excluir
- Super Administradores com acesso total
- Middleware de verificação de permissões

### ✅ Interface de Usuário
- Interface moderna e responsiva
- Formulários com validação
- Checkboxes organizados por módulo
- Página de erro 403 amigável
- Integração com o menu lateral existente

### ✅ Segurança
- Validação de formulários
- Proteção contra auto-exclusão
- Proteção do último superadmin
- Auditoria com registro de quem atualizou
- Hash de senhas

## Estrutura do Banco de Dados

### Tabela `admin_permissions`
```sql
- id (pk)
- admin_id (fk -> admin.id, on delete cascade)
- menu_key (varchar 50)
- can_view (bool) default 0
- can_create (bool) default 0
- can_update (bool) default 0
- can_delete (bool) default 0
- created_at, updated_at
- updated_by (nullable fk -> admin.id)
UNIQUE(admin_id, menu_key)
```

### Campos adicionados na tabela `admins`
```sql
- status (tinyint) default 1
- last_login_at (timestamp nullable)
- updated_by (nullable fk -> admin.id)
- is_superadmin (bool) default false
```

## Como Usar

### 1. Executar as Migrations
```bash
php artisan migrate
```

### 2. Executar o Seeder (Opcional)
Para criar usuários de exemplo com permissões:
```bash
php artisan db:seed --class=AdminPermissionsSeeder
```

Isso criará:
- **Super Admin**: `superadmin@amcig.com` / `superadmin123`
- **Admin Limitado**: `admin.limitado@amcig.com` / `admin123`
- **Admin Completo**: `admin.completo@amcig.com` / `admin123`

### 3. Acessar o Módulo
1. Faça login como administrador
2. No menu lateral, vá em **Configurações > Usuários Administrativos**
3. Gerencie os usuários e suas permissões

## Aplicando Permissões nas Rotas

### Exemplo de Uso do Middleware
```php
// Rota com permissão de visualização
Route::middleware(['check.permission:associados,view'])->group(function() {
    Route::get('/associados', 'AssociadoController@index');
});

// Rota com permissão de criação
Route::middleware(['check.permission:associados,create'])->group(function() {
    Route::post('/associados', 'AssociadoController@store');
});

// Rota com permissão de atualização
Route::middleware(['check.permission:associados,update'])->group(function() {
    Route::put('/associados/{id}', 'AssociadoController@update');
});

// Rota com permissão de exclusão
Route::middleware(['check.permission:associados,delete'])->group(function() {
    Route::delete('/associados/{id}', 'AssociadoController@destroy');
});
```

### Verificando Permissões no Controller
```php
// Verificar se tem permissão
if (!auth('admin')->user()->hasPermission('associados', 'create')) {
    abort(403, 'Acesso negado');
}

// Verificar se é superadmin
if (auth('admin')->user()->isSuperAdmin()) {
    // Lógica para superadmin
}
```

### Verificando Permissões na View
```blade
@if(auth('admin')->user()->hasPermission('associados', 'create'))
    <a href="{{ route('admin.associados.create') }}" class="btn btn-primary">
        Novo Associado
    </a>
@endif
```

## Menus Disponíveis

| Menu Key | Nome | Descrição |
|----------|------|-----------|
| `associados` | Associados | Gestão de associados |
| `eventos` | Eventos | Gestão de eventos |
| `parcerias` | Parcerias | Gestão de parcerias |
| `financeiro` | Financeiro | Módulo financeiro |
| `solicitacoes` | Solicitações | Gestão de solicitações |
| `config_sistema` | Configurações do Sistema | Configurações gerais |
| `contas_bancarias` | Contas Bancárias | Gestão de contas bancárias |

## Ações Disponíveis

| Ação | Descrição |
|------|-----------|
| `view` | Visualizar/Listar registros |
| `create` | Criar novos registros |
| `update` | Editar registros existentes |
| `delete` | Excluir registros |

## Regras de Negócio

1. **Super Administradores**: Têm acesso total e ignoram verificações de permissão
2. **Auto-proteção**: Usuários não podem excluir ou desativar a si mesmos
3. **Último Superadmin**: Não pode ser excluído ou desativado
4. **Auditoria**: Todas as alterações registram quem fez a modificação
5. **Permissões**: Se um usuário não tem permissão de visualizar, não pode ter outras permissões para aquele módulo

## Arquivos Criados/Modificados

### Migrations
- `2025_10_11_182105_create_admin_permissions_table.php`
- `2025_10_11_182232_add_fields_to_admins_table.php`

### Models
- `AdminPermission.php` (novo)
- `Admin.php` (atualizado)

### Controllers
- `AdminUserController.php` (novo)

### Form Requests
- `StoreAdminRequest.php` (novo)
- `UpdateAdminRequest.php` (novo)

### Middleware
- `CheckAdminPermission.php` (novo)
- `Kernel.php` (atualizado)

### Views
- `admin/users/index.blade.php` (novo)
- `admin/users/create.blade.php` (novo)
- `admin/users/edit.blade.php` (novo)
- `admin/users/show.blade.php` (novo)
- `errors/403.blade.php` (novo)
- `layouts/admin.blade.php` (atualizado)

### Seeders
- `AdminPermissionsSeeder.php` (novo)
- `AdminSeeder.php` (atualizado)
- `DatabaseSeeder.php` (atualizado)

### Rotas
- `web.php` (atualizado com novas rotas e middleware)

## Próximos Passos (Opcionais)

1. **Logs de Auditoria**: Implementar logs detalhados de todas as ações
2. **2FA**: Integrar autenticação de dois fatores
3. **Notificações**: Notificar admins sobre alterações de permissão
4. **Backup**: Sistema de backup das permissões
5. **API**: Endpoints para integração com outros sistemas

## Troubleshooting

### Erro 403 ao acessar módulos
- Verifique se o usuário tem as permissões necessárias
- Confirme se o middleware está aplicado corretamente nas rotas

### Usuário não consegue criar outros usuários
- Verifique se tem permissão `config_sistema.create`
- Confirme se não é o último superadmin

### Permissões não estão sendo aplicadas
- Verifique se as migrations foram executadas
- Confirme se o seeder foi executado
- Verifique se o middleware está registrado no Kernel

## Suporte

Para dúvidas ou problemas, verifique:
1. Logs do Laravel (`storage/logs/laravel.log`)
2. Se todas as migrations foram executadas
3. Se o middleware está registrado corretamente
4. Se as permissões estão salvas no banco de dados
