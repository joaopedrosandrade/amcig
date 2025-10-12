@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Novo Usuário Administrativo</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.config.usuarios.index') }}">Usuários Administrativos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Novo Usuário</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dados do Usuário</h5>
                    </div>

                    <div class="card-body">
                            <form action="{{ route('admin.config.usuarios.store') }}" method="POST">
                                @csrf
                                
                                <!-- Dados Básicos -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" 
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Senha <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" 
                                                   id="password" name="password" required>
                                            @if($errors->has('password'))
                                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                            @if($errors->has('password_confirmation'))
                                                <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Configurações -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" 
                                                       {{ old('status', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">
                                                    Status Ativo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_superadmin" name="is_superadmin" value="1" 
                                                       {{ old('is_superadmin') ? 'checked' : '' }}
                                                       onchange="togglePermissions()">
                                                <label class="form-check-label" for="is_superadmin">
                                                    Super Administrador
                                                </label>
                                            </div>
                                            <small class="text-muted">Super administradores têm acesso total ao sistema e não precisam de permissões específicas.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissões -->
                                <div id="permissions-section">
                                    <hr>
                                    <h5 class="mb-3">Permissões por Módulo</h5>
                                    
                                    <div class="row">
                                        @foreach($menus as $menuKey => $menuName)
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">{{ $menuName }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   id="permissions[{{ $menuKey }}][can_view]" 
                                                                   name="permissions[{{ $menuKey }}][can_view]" 
                                                                   value="1"
                                                                   {{ old("permissions.{$menuKey}.can_view") ? 'checked' : '' }}
                                                                   onchange="toggleMenuPermissions('{{ $menuKey }}')">
                                                            <label class="form-check-label" for="permissions[{{ $menuKey }}][can_view]">
                                                                <i class="ri-eye-line me-1"></i> Visualizar
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input permission-checkbox permission-{{ $menuKey }}" 
                                                                   type="checkbox" 
                                                                   id="permissions[{{ $menuKey }}][can_create]" 
                                                                   name="permissions[{{ $menuKey }}][can_create]" 
                                                                   value="1"
                                                                   {{ old("permissions.{$menuKey}.can_create") ? 'checked' : '' }}
                                                                   disabled>
                                                            <label class="form-check-label" for="permissions[{{ $menuKey }}][can_create]">
                                                                <i class="ri-add-line me-1"></i> Criar
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input permission-checkbox permission-{{ $menuKey }}" 
                                                                   type="checkbox" 
                                                                   id="permissions[{{ $menuKey }}][can_update]" 
                                                                   name="permissions[{{ $menuKey }}][can_update]" 
                                                                   value="1"
                                                                   {{ old("permissions.{$menuKey}.can_update") ? 'checked' : '' }}
                                                                   disabled>
                                                            <label class="form-check-label" for="permissions[{{ $menuKey }}][can_update]">
                                                                <i class="ri-edit-line me-1"></i> Editar
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox permission-{{ $menuKey }}" 
                                                                   type="checkbox" 
                                                                   id="permissions[{{ $menuKey }}][can_delete]" 
                                                                   name="permissions[{{ $menuKey }}][can_delete]" 
                                                                   value="1"
                                                                   {{ old("permissions.{$menuKey}.can_delete") ? 'checked' : '' }}
                                                                   disabled>
                                                            <label class="form-check-label" for="permissions[{{ $menuKey }}][can_delete]">
                                                                <i class="ri-delete-bin-line me-1"></i> Excluir
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.config.usuarios.index') }}" class="btn btn-secondary">
                                                <i class="ri-arrow-left-line me-1"></i> Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line me-1"></i> Salvar Usuário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function togglePermissions() {
    const isSuperAdmin = document.getElementById('is_superadmin').checked;
    const permissionsSection = document.getElementById('permissions-section');
    
    if (isSuperAdmin) {
        permissionsSection.style.display = 'none';
        // Desmarcar todas as permissões
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.disabled = true;
        });
    } else {
        permissionsSection.style.display = 'block';
        // Reabilitar checkboxes de visualizar
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            if (checkbox.name.includes('[can_view]')) {
                checkbox.disabled = false;
            }
        });
    }
}

function toggleMenuPermissions(menuKey) {
    const viewCheckbox = document.querySelector(`input[name="permissions[${menuKey}][can_view]"]`);
    const otherCheckboxes = document.querySelectorAll(`.permission-${menuKey}`);
    
    otherCheckboxes.forEach(checkbox => {
        checkbox.disabled = !viewCheckbox.checked;
        if (!viewCheckbox.checked) {
            checkbox.checked = false;
        }
    });
}

// Inicializar estado das permissões
document.addEventListener('DOMContentLoaded', function() {
    togglePermissions();
    
    // Aplicar estado das permissões para cada menu
    @foreach($menus as $menuKey => $menuName)
        toggleMenuPermissions('{{ $menuKey }}');
    @endforeach
});
</script>
@endsection
