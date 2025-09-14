@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Nova Parceria</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.parcerias.index') }}">Parcerias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nova Parceria</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dados da Parceria</h5>
                    </div>
                    <div class="card-body">
                        <form id="parceriaForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Dados Básicos -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="ri-building-line me-2"></i>Dados da Empresa
                                    </h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nome_empresa" class="form-label">Nome da Empresa <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                                        <select class="form-select" id="categoria" name="categoria" required>
                                            <option value="">Selecione...</option>
                                            @foreach($categorias as $key => $nome)
                                                <option value="{{ $key }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descrição breve da empresa e serviços oferecidos..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 0000-0000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="contato@empresa.com">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="website" name="website" placeholder="https://www.empresa.com">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço completo da empresa">
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo da Empresa</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/jpeg,image/jpg,image/png">
                                <div class="form-text">Formatos aceitos: JPG, JPEG, PNG. Tamanho máximo: 2MB</div>
                                <div id="logo-preview" class="mt-2" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                                </div>
                            </div>

                            <!-- Configurações de Desconto -->
                            <div class="row mb-4 mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="ri-percent-line me-2"></i>Configurações de Desconto
                                    </h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tipo_desconto" class="form-label">Tipo de Desconto <span class="text-danger">*</span></label>
                                        <select class="form-select" id="tipo_desconto" name="tipo_desconto" required>
                                            <option value="">Selecione...</option>
                                            @foreach($tiposDesconto as $key => $nome)
                                                <option value="{{ $key }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="valor_desconto" class="form-label">Valor do Desconto <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="valor_desconto" name="valor_desconto" 
                                               step="0.01" min="0" required placeholder="0.00">
                                        <div class="form-text" id="help_valor_desconto">
                                            Digite o valor do desconto (ex: 10 para 10% ou 5.00 para R$ 5,00)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="valor_minimo_pedido" class="form-label">Valor Mínimo do Pedido</label>
                                        <input type="number" class="form-control" id="valor_minimo_pedido" name="valor_minimo_pedido" 
                                               step="0.01" min="0" placeholder="0.00">
                                        <div class="form-text">Deixe em branco se não houver valor mínimo</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="condicoes_desconto" class="form-label">Condições Especiais</label>
                                <textarea class="form-control" id="condicoes_desconto" name="condicoes_desconto" rows="2" 
                                          placeholder="Condições especiais do desconto (ex: Válido apenas aos sábados, Não acumula com outras promoções...)"></textarea>
                            </div>

                            <!-- Configurações Adicionais -->
                            <div class="row mb-4 mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="ri-settings-3-line me-2"></i>Configurações Adicionais
                                    </h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ordem" class="form-label">Ordem de Exibição</label>
                                        <input type="number" class="form-control" id="ordem" name="ordem" min="0" value="0">
                                        <div class="form-text">Menor número = aparece primeiro</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" checked>
                                            <label class="form-check-label" for="ativo">
                                                Parceria ativa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="destaque" name="destaque" value="1">
                                            <label class="form-check-label" for="destaque">
                                                Exibir em destaque
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('admin.parcerias.index') }}" class="btn btn-secondary me-2">
                                    <i class="ri-arrow-left-line me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Salvar Parceria
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Máscara para telefone
    $('#telefone').mask('(00) 00000-0000');

    // Atualizar ajuda do valor de desconto baseado no tipo
    $('#tipo_desconto').on('change', function() {
        const tipo = $(this).val();
        const helpText = $('#help_valor_desconto');
        
        switch(tipo) {
            case 'percentual':
                helpText.text('Digite o percentual (ex: 10 para 10%)');
                $('#valor_desconto').attr('max', '100');
                break;
            case 'valor_fixo':
                helpText.text('Digite o valor em reais (ex: 5.00 para R$ 5,00)');
                $('#valor_desconto').removeAttr('max');
                break;
            case 'desconto_especial':
                helpText.text('Este campo será ignorado - use "Condições Especiais"');
                $('#valor_desconto').removeAttr('max');
                break;
            default:
                helpText.text('Digite o valor do desconto');
                $('#valor_desconto').removeAttr('max');
        }
    });

    // Preview da imagem
    $('#logo').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#logo-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#logo-preview').hide();
        }
    });

    // Formulário de submissão
    $('#parceriaForm').on('submit', function(e) {
        e.preventDefault();
        
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        // Desabilitar botão e mostrar loading
        $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Salvando...');
        
        const formData = new FormData(this);
        
        console.log('Enviando dados:', formData);
        console.log('URL:', '{{ route("admin.parcerias.store") }}');
        
        $.ajax({
            url: '{{ route("admin.parcerias.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Resposta recebida:', response);
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Erro ao salvar parceria');
                }
            },
            error: function(xhr) {
                console.log('Erro:', xhr);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erro de validação:\n';
                    
                    Object.keys(errors).forEach(function(key) {
                        errorMessage += '- ' + errors[key][0] + '\n';
                    });
                    
                    toastr.error(errorMessage);
                } else {
                    const errorMsg = xhr.responseJSON?.message || 'Erro ao salvar parceria';
                    toastr.error(errorMsg);
                }
            },
            complete: function() {
                // Reabilitar botão
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush
