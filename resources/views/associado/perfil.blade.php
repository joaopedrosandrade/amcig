@extends('layouts.associado')

@section('title', 'Meu Perfil - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Meu Perfil</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Meu Perfil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        <!-- Mensagens de Sucesso/Erro -->
        @if(session('success'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <!-- Foto do Perfil -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-line me-2"></i>Foto do Perfil
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->photo_url }}" 
                                     alt="Foto do {{ $user->name }}" 
                                     class="rounded-circle shadow-lg" 
                                     id="profile-photo"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <!-- Overlay para upload -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle d-flex align-items-center justify-content-center" 
                                     style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s; cursor: pointer;"
                                     id="photo-overlay"
                                     onclick="document.getElementById('photo-input').click()">
                                    <i class="ri-camera-line text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            <small class="text-muted">Matrícula: {{ $user->matricula }}</small>
                        </div>
                        
                        <!-- Área para alertas de upload -->
                        <div id="upload-alerts" class="mb-3">
                            <!-- Alertas serão inseridos aqui via JavaScript -->
                        </div>
                        
                        <!-- Informações do Preview (ocultas inicialmente) -->
                        <div id="photo-preview-info" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="ri-image-line me-2"></i>Nova Foto Selecionada</h6>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1" id="preview-filename"></p>
                                        <small class="text-muted" id="preview-size"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação -->
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('photo-input').click()">
                                <i class="ri-upload-line me-1"></i>Selecionar Foto
                            </button>
                            
                            <!-- Botão de Confirmar Upload (oculto inicialmente) -->
                            <button type="button" class="btn btn-success" id="confirm-upload-btn" style="display: none;" onclick="confirmUpload()">
                                <i class="ri-check-line me-1"></i>Confirmar Upload
                            </button>
                            
                            <!-- Botão de Cancelar Upload (oculto inicialmente) -->
                            <button type="button" class="btn btn-outline-secondary" id="cancel-upload-btn" style="display: none;" onclick="cancelUpload()">
                                <i class="ri-close-line me-1"></i>Cancelar
                            </button>
                            
                            @if($user->photo)
                                <button type="button" class="btn btn-outline-danger" onclick="removePhoto()">
                                    <i class="ri-delete-bin-line me-1"></i>Remover Foto
                                </button>
                            @endif
                        </div>
                        
                        <!-- Input oculto para upload -->
                        <input type="file" 
                               id="photo-input" 
                               accept="image/*" 
                               style="display: none;"
                               onchange="previewPhoto(this)">
                    </div>
                </div>

                <!-- Informações Pessoais -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-settings-line me-2"></i>Informações Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CPF</label>
                                <input type="text" class="form-control" value="{{ $user->cpf }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data de Nascimento</label>
                                <input type="text" class="form-control" value="{{ $user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : '' }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" value="{{ $user->telefone }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Associado</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->tipo_associado) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-map-pin-line me-2"></i>Endereço
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">CEP</label>
                                <input type="text" class="form-control" value="{{ $user->cep }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logradouro</label>
                                <input type="text" class="form-control" value="{{ $user->logradouro }}" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" value="{{ $user->numero }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Complemento</label>
                                <input type="text" class="form-control" value="{{ $user->complemento }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bairro</label>
                                <input type="text" class="form-control" value="{{ $user->bairro }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" value="{{ $user->cidade }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">UF</label>
                                <input type="text" class="form-control" value="{{ $user->uf }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Comércio (se aplicável) -->
                @if($user->tipo_associado === 'comerciante' || $user->tipo_associado === 'ambos')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-store-line me-2"></i>Informações do Comércio
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome do Comércio</label>
                                    <input type="text" class="form-control" value="{{ $user->nome_comercio }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ramo de Atividade</label>
                                    <input type="text" class="form-control" value="{{ $user->ramo_atividade }}" readonly>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Endereço do Comércio</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $user->endereco_comercio }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('associado.dashboard') }}" class="btn btn-secondary me-2">
                            <i class="ri-arrow-left-line me-1"></i>Voltar ao Dashboard
                        </a>
                        <a href="{{ route('associado.pagamentos') }}" class="btn btn-primary">
                            <i class="ri-file-list-line me-1"></i>Minhas Mensalidades
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--End container-fluid-->
</main>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- Conteúdo será inserido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mostrar overlay ao passar o mouse sobre a foto
    document.getElementById('profile-photo').addEventListener('mouseenter', function() {
        document.getElementById('photo-overlay').style.opacity = '1';
    });

    document.getElementById('profile-photo').addEventListener('mouseleave', function() {
        document.getElementById('photo-overlay').style.opacity = '0';
    });

    // Variável para armazenar o arquivo selecionado
    let selectedFile = null;
    let originalPhotoUrl = null;

    // Função para fazer preview da foto
    window.previewPhoto = function(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tamanho do arquivo (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('error', 'A imagem não pode ser maior que 2MB.');
                input.value = '';
                return;
            }
            
            // Validar tipo do arquivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showAlert('error', 'A imagem deve ser do tipo: JPEG, PNG, JPG ou GIF.');
                input.value = '';
                return;
            }
            
            // Armazenar arquivo selecionado
            selectedFile = file;
            
            // Salvar URL original da foto
            if (!originalPhotoUrl) {
                originalPhotoUrl = document.getElementById('profile-photo').src;
            }
            
            // Criar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                // Mostrar preview no avatar principal
                document.getElementById('profile-photo').src = e.target.result;
                
                // Mostrar informações do arquivo
                document.getElementById('preview-filename').textContent = file.name;
                document.getElementById('preview-size').textContent = formatFileSize(file.size);
                
                // Mostrar área de informações
                document.getElementById('photo-preview-info').style.display = 'block';
                
                // Mostrar botões de confirmação
                document.getElementById('confirm-upload-btn').style.display = 'inline-block';
                document.getElementById('cancel-upload-btn').style.display = 'inline-block';
                
                // Ocultar botão de selecionar
                document.querySelector('button[onclick*="photo-input"]').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    };

    // Função para confirmar upload
    window.confirmUpload = function() {
        if (!selectedFile) {
            showAlert('error', 'Nenhum arquivo selecionado.');
            return;
        }
        
        // Mostrar loading no botão de confirmar
        const confirmBtn = document.getElementById('confirm-upload-btn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        confirmBtn.disabled = true;
        
        // Criar FormData
        const formData = new FormData();
        formData.append('photo', selectedFile);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Debug: verificar o que está sendo enviado
        console.log('Arquivo sendo enviado:', {
            name: selectedFile.name,
            size: selectedFile.size,
            type: selectedFile.type,
            lastModified: selectedFile.lastModified
        });
        
        // Debug: verificar FormData
        for (let pair of formData.entries()) {
            console.log('FormData:', pair[0], pair[1]);
        }
        
        // Fazer upload
        fetch('{{ route("associado.perfil.foto") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualizar imagem principal
                document.getElementById('profile-photo').src = data.photo_url;
                
                // Mostrar mensagem de sucesso
                showAlert('success', data.message);
                
                // Limpar preview e restaurar estado
                selectedFile = null;
                originalPhotoUrl = null;
                
                // Ocultar área de informações
                document.getElementById('photo-preview-info').style.display = 'none';
                
                // Ocultar botões de confirmação
                document.getElementById('confirm-upload-btn').style.display = 'none';
                document.getElementById('cancel-upload-btn').style.display = 'none';
                
                // Mostrar botão de selecionar
                document.querySelector('button[onclick*="photo-input"]').style.display = 'inline-block';
                
                // Atualizar botão de remover foto
                updateRemoveButton();
            } else {
                // Mostrar erro principal
                showAlert('error', data.message);
                
                // Mostrar erros de validação específicos se existirem
                if (data.errors) {
                    let errorMessages = [];
                    for (let field in data.errors) {
                        if (data.errors[field] && data.errors[field].length > 0) {
                            errorMessages = errorMessages.concat(data.errors[field]);
                        }
                    }
                    if (errorMessages.length > 0) {
                        showAlert('error', errorMessages.join('<br>'));
                    }
                }
                
                // Cancelar upload em caso de erro
                cancelUpload();
            }
        })
        .catch(error => {
            console.error('Erro ao fazer upload:', error);
            showAlert('error', 'Erro ao fazer upload da foto. Tente novamente.');
        })
        .finally(() => {
            // Restaurar botão
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
    };

    // Função para cancelar upload
    window.cancelUpload = function() {
        // Limpar arquivo selecionado
        selectedFile = null;
        
        // Restaurar foto original
        if (originalPhotoUrl) {
            document.getElementById('profile-photo').src = originalPhotoUrl;
        }
        
        // Limpar input
        document.getElementById('photo-input').value = '';
        
        // Ocultar área de informações
        document.getElementById('photo-preview-info').style.display = 'none';
        
        // Ocultar botões de confirmação
        document.getElementById('confirm-upload-btn').style.display = 'none';
        document.getElementById('cancel-upload-btn').style.display = 'none';
        
        // Mostrar botão de selecionar
        document.querySelector('button[onclick*="photo-input"]').style.display = 'inline-block';
    };

    // Função para formatar tamanho do arquivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Função para remover foto
    window.removePhoto = function() {
    document.getElementById('confirmModalBody').innerHTML = 
        '<p>Tem certeza que deseja remover sua foto de perfil?</p>' +
        '<p class="text-muted">Uma foto padrão será exibida baseada nas suas iniciais.</p>';
    
    document.getElementById('confirmButton').onclick = function() {
        // Mostrar loading
        const button = this;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Removendo...';
        button.disabled = true;
        
        fetch('{{ route("associado.perfil.foto.remove") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualizar imagem
                document.getElementById('profile-photo').src = data.photo_url;
                
                // Mostrar mensagem de sucesso
                showAlert('success', data.message);
                
                // Atualizar botão de remover foto
                updateRemoveButton();
                
                // Fechar modal
                bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao remover foto:', error);
            showAlert('error', 'Erro ao remover foto. Tente novamente.');
        })
        .finally(() => {
            // Restaurar botão
            button.innerHTML = originalText;
            button.disabled = false;
        });
    };
    
    // Mostrar modal
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
    };

    // Função para atualizar botão de remover foto
    window.updateRemoveButton = function() {
        const removeButton = document.querySelector('button[onclick="removePhoto()"]');
        if (removeButton) {
            // Verificar se há foto atual
            const currentPhoto = document.getElementById('profile-photo').src;
            const hasPhoto = !currentPhoto.includes('ui-avatars.com');
            
            if (hasPhoto) {
                removeButton.style.display = 'inline-block';
            } else {
                removeButton.style.display = 'none';
            }
        }
    };

    // Função para mostrar alertas
    window.showAlert = function(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line me-2"></i>
                <span class="alert-message">${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Inserir alerta na área específica do upload
        const alertContainer = document.getElementById('upload-alerts');
        if (alertContainer) {
            alertContainer.innerHTML = alertHtml;
        } else {
            // Fallback para o topo da página se a área não existir
            const container = document.querySelector('.container-fluid');
            container.insertAdjacentHTML('afterbegin', alertHtml);
        }
        
        // Remover alerta após 8 segundos (mais tempo para ler erros)
        setTimeout(() => {
            const alert = alertContainer ? alertContainer.querySelector('.alert') : document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 8000);
    };

    // Auto-dismiss alerts após 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Atualizar botão de remover foto na inicialização
    updateRemoveButton();
});
</script>
@endpush
