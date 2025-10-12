@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">{{ $admin->name }}</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.config.usuarios.index') }}">Usuários Administrativos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $admin->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Informações do Usuário</h5>
                                <div class="btn-group">
                                    @if(auth('admin')->user()->hasPermission('config_sistema', 'update'))
                                        <a href="{{ route('admin.config.usuarios.edit', $admin) }}" class="btn btn-primary btn-sm">
                                            <i class="ri-edit-line me-1"></i> Editar
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.config.usuarios.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="ri-arrow-left-line me-1"></i> Voltar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Dados Básicos -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <span class="text-white fs-24 fw-bold">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">{{ $admin->name }}</h4>
                                            <p class="text-muted mb-0">{{ $admin->email }}</p>
                                            @if($admin->is_superadmin)
                                                <span class="badge bg-danger mt-1">Super Administrador</span>
                                            @else
                                                <span class="badge bg-info mt-1">Administrador</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-end">
                                        <div class="mb-2">
                                            <span class="badge bg-{{ $admin->status ? 'success' : 'secondary' }} fs-12">
                                                {{ $admin->status ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>
                                        @if(auth('admin')->user()->hasPermission('config_sistema', 'update') && $admin->id !== auth('admin')->id())
                                            <form action="{{ route('admin.config.usuarios.toggle-status', $admin) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-{{ $admin->status ? 'warning' : 'success' }}" 
                                                        onclick="return confirm('Tem certeza que deseja {{ $admin->status ? 'desativar' : 'ativar' }} este usuário?')">
                                                    <i class="ri-{{ $admin->status ? 'user-unfollow-line' : 'user-follow-line' }} me-1"></i>
                                                    {{ $admin->status ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Detalhadas -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Informações da Conta</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td>{{ $admin->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nome:</strong></td>
                                            <td>{{ $admin->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $admin->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipo:</strong></td>
                                            <td>
                                                @if($admin->is_superadmin)
                                                    <span class="badge bg-danger">Super Administrador</span>
                                                @else
                                                    <span class="badge bg-info">Administrador</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($admin->status)
                                                    <span class="badge bg-success">Ativo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inativo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Informações de Acesso</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Criado em:</strong></td>
                                            <td>{{ $admin->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Última atualização:</strong></td>
                                            <td>{{ $admin->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Último acesso:</strong></td>
                                            <td>
                                                @if($admin->last_login_at)
                                                    {{ $admin->last_login_at->format('d/m/Y H:i:s') }}
                                                    <br>
                                                    <small class="text-muted">{{ $admin->last_login_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Nunca acessou</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Atualizado por:</strong></td>
                                            <td>
                                                @if($admin->updatedBy)
                                                    {{ $admin->updatedBy->name }}
                                                @else
                                                    <span class="text-muted">Sistema</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Permissões -->
                            @if(!$admin->is_superadmin)
                                <hr>
                                <h5 class="mb-3">Permissões por Módulo</h5>
                                
                                <div class="row">
                                    @foreach($permissions as $menuKey => $permission)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card border">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">{{ $permission['name'] }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @if($permission['can_view'])
                                                            <span class="badge bg-primary">
                                                                <i class="ri-eye-line me-1"></i> Visualizar
                                                            </span>
                                                        @endif
                                                        @if($permission['can_create'])
                                                            <span class="badge bg-success">
                                                                <i class="ri-add-line me-1"></i> Criar
                                                            </span>
                                                        @endif
                                                        @if($permission['can_update'])
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="ri-edit-line me-1"></i> Editar
                                                            </span>
                                                        @endif
                                                        @if($permission['can_delete'])
                                                            <span class="badge bg-danger">
                                                                <i class="ri-delete-bin-line me-1"></i> Excluir
                                                            </span>
                                                        @endif
                                                        @if(!$permission['can_view'] && !$permission['can_create'] && !$permission['can_update'] && !$permission['can_delete'])
                                                            <span class="text-muted">Sem permissões</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="ri-shield-star-line me-1"></i> Super Administrador
                                    </h6>
                                    <p class="mb-0">Este usuário é um super administrador e possui acesso total a todos os módulos do sistema.</p>
                                </div>
                            @endif

                            <!-- Ações -->
                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                @if(auth('admin')->user()->hasPermission('config_sistema', 'delete') && $admin->id !== auth('admin')->id())
                                    <form action="{{ route('admin.config.usuarios.destroy', $admin) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ri-delete-bin-line me-1"></i> Excluir Usuário
                                        </button>
                                    </form>
                                @endif
                                @if(auth('admin')->user()->hasPermission('config_sistema', 'update'))
                                    <a href="{{ route('admin.config.usuarios.edit', $admin) }}" class="btn btn-primary">
                                        <i class="ri-edit-line me-1"></i> Editar Usuário
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
