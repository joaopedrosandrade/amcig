@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Usuários Administrativos</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Configurações</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Usuários Administrativos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-line me-2"></i>Lista de Usuários Administrativos
                        </h5>
                        @if(auth('admin')->user()->hasPermission('config_sistema', 'create'))
                            <a href="{{ route('admin.config.usuarios.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Novo Usuário
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.config.usuarios.index') }}" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2" 
                                           placeholder="Buscar por nome ou email..." 
                                           value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Todos os status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tabela -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Último Acesso</th>
                                        <th>Atualizado Por</th>
                                        <th width="120">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $admin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <span class="text-white fw-bold">
                                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $admin->name }}</h6>
                                                        <small class="text-muted">ID: {{ $admin->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                @if($admin->is_superadmin)
                                                    <span class="badge bg-danger">Super Admin</span>
                                                @else
                                                    <span class="badge bg-info">Admin</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($admin->status)
                                                    <span class="badge bg-success">Ativo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inativo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($admin->last_login_at)
                                                    {{ $admin->last_login_at->format('d/m/Y H:i') }}
                                                    <br>
                                                    <small class="text-muted">{{ $admin->last_login_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Nunca</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($admin->updatedBy)
                                                    {{ $admin->updatedBy->name }}
                                                    <br>
                                                    <small class="text-muted">{{ $admin->updated_at->format('d/m/Y H:i') }}</small>
                                                @else
                                                    <span class="text-muted">Sistema</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'view'))
                                                        <a href="{{ route('admin.config.usuarios.show', $admin) }}" 
                                                           class="btn btn-sm btn-outline-info" title="Visualizar">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'update'))
                                                        <a href="{{ route('admin.config.usuarios.edit', $admin) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Editar">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'update') && $admin->id !== auth('admin')->id())
                                                        <form action="{{ route('admin.config.usuarios.toggle-status', $admin) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-{{ $admin->status ? 'warning' : 'success' }}" 
                                                                    title="{{ $admin->status ? 'Desativar' : 'Ativar' }}"
                                                                    onclick="return confirm('Tem certeza que deseja {{ $admin->status ? 'desativar' : 'ativar' }} este usuário?')">
                                                                <i class="ri-{{ $admin->status ? 'user-unfollow-line' : 'user-follow-line' }}"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'delete') && $admin->id !== auth('admin')->id())
                                                        <form action="{{ route('admin.config.usuarios.destroy', $admin) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita!')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ri-user-line fs-48 mb-3 d-block"></i>
                                                    <p>Nenhum usuário administrativo encontrado.</p>
                                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'create'))
                                                        <a href="{{ route('admin.config.usuarios.create') }}" class="btn btn-primary">
                                                            Criar primeiro usuário
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        @if($admins->hasPages())
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Mostrando {{ $admins->firstItem() }} a {{ $admins->lastItem() }} de {{ $admins->total() }} registros
                                    </div>
                                    <div>
                                        {{ $admins->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Auto-submit do filtro de status
document.querySelector('select[name="status"]').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection