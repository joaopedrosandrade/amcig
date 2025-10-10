@extends('layouts.admin')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Nova Conta a Pagar</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Financeiro</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fluxo-caixa.contas-pagar') }}">Contas a Pagar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nova Conta</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Formulário de Cadastro -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cadastrar Conta a Pagar</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.fluxo-caixa.contas-pagar.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Informações Básicas -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-information-line me-2"></i>Informações Básicas</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}" 
                                           id="descricao" name="descricao" value="{{ old('descricao') }}" required>
                                    @if($errors->has('descricao'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('descricao') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select {{ $errors->has('categoria_id') ? 'is-invalid' : '' }}" 
                                                id="categoria_id" name="categoria_id" required>
                                            <option value="">Selecione...</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria" title="Nova Categoria">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                    @if($errors->has('categoria_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('categoria_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="valor" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" 
                                           id="valor" name="valor" value="{{ old('valor') }}" 
                                           step="0.01" min="0" required>
                                    @if($errors->has('valor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('valor') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="data_vencimento" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control {{ $errors->has('data_vencimento') ? 'is-invalid' : '' }}" 
                                           id="data_vencimento" name="data_vencimento" 
                                           value="{{ old('data_vencimento') }}" required>
                                    @if($errors->has('data_vencimento'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('data_vencimento') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="data_competencia" class="form-label">Data de Competência (Mês/Ano)</label>
                                    <input type="date" class="form-control {{ $errors->has('data_competencia') ? 'is-invalid' : '' }}" 
                                           id="data_competencia" name="data_competencia" 
                                           value="{{ old('data_competencia') }}">
                                    @if($errors->has('data_competencia'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('data_competencia') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dados do Fornecedor -->
                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <h6 class="text-primary mb-0"><i class="ri-building-line me-2"></i>Fornecedor</h6>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="fornecedor_id" class="form-label">Buscar Fornecedor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select {{ $errors->has('fornecedor_id') ? 'is-invalid' : '' }}" 
                                                id="fornecedor_id" name="fornecedor_id" required style="width: 100%">
                                            <option value="">Digite para buscar...</option>
                                            @foreach($fornecedores as $fornecedor)
                                                <option value="{{ $fornecedor->id }}" {{ old('fornecedor_id') == $fornecedor->id ? 'selected' : '' }}>
                                                    {{ $fornecedor->nome_completo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor" title="Novo Fornecedor">
                                            <i class="ri-add-line"></i> Novo
                                        </button>
                                    </div>
                                    @if($errors->has('fornecedor_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('fornecedor_id') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dados da Nota Fiscal -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-file-text-line me-2"></i>Dados da Nota Fiscal (Opcional)</h6>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="numero_nota_fiscal" class="form-label">Número da NF</label>
                                    <input type="text" class="form-control" id="numero_nota_fiscal" name="numero_nota_fiscal" value="{{ old('numero_nota_fiscal') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="serie_nota_fiscal" class="form-label">Série da NF</label>
                                    <input type="text" class="form-control" id="serie_nota_fiscal" name="serie_nota_fiscal" value="{{ old('serie_nota_fiscal') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="data_emissao_nota" class="form-label">Data de Emissão</label>
                                    <input type="date" class="form-control" id="data_emissao_nota" name="data_emissao_nota" value="{{ old('data_emissao_nota') }}">
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="chave_acesso_nfe" class="form-label">Chave de Acesso NFe</label>
                                    <input type="text" class="form-control" id="chave_acesso_nfe" name="chave_acesso_nfe" 
                                           value="{{ old('chave_acesso_nfe') }}" maxlength="44" placeholder="44 dígitos">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="arquivo_nota_fiscal" class="form-label">Anexar NF (PDF/Imagem)</label>
                                    <input type="file" class="form-control" id="arquivo_nota_fiscal" name="arquivo_nota_fiscal" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Máx: 5MB</small>
                                </div>
                            </div>

                            <!-- Parcelamento -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-bank-card-line me-2"></i>Parcelamento (Opcional)</h6>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="parcelado" name="parcelado" value="1" {{ old('parcelado') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="parcelado">Esta conta é parcelada</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3" id="numero_parcela_div" style="display: none;">
                                    <label for="numero_parcela" class="form-label">Número da Parcela</label>
                                    <input type="number" class="form-control" id="numero_parcela" name="numero_parcela" value="{{ old('numero_parcela') }}" min="1">
                                </div>

                                <div class="col-md-4 mb-3" id="total_parcelas_div" style="display: none;">
                                    <label for="total_parcelas" class="form-label">Total de Parcelas</label>
                                    <input type="number" class="form-control" id="total_parcelas" name="total_parcelas" value="{{ old('total_parcelas') }}" min="1">
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="observacoes" class="form-label">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Cadastrar Conta
                                    </button>
                                    <a href="{{ route('admin.fluxo-caixa.contas-pagar') }}" class="btn btn-secondary">
                                        <i class="ri-close-line me-1"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Novo Fornecedor -->
<div class="modal fade" id="modalNovoFornecedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Novo Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNovoFornecedor">
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fornecedor_nome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="fornecedor_cnpj">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="fornecedor_telefone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="fornecedor_email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="salvarFornecedor()">
                    <i class="ri-save-line me-1"></i> Salvar Fornecedor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Categoria -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNovaCategoria">
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoria_nome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cor (opcional)</label>
                        <input type="color" class="form-control form-control-color" id="categoria_cor" value="#0d6efd">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" id="categoria_descricao" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarCategoria()">
                    <i class="ri-save-line me-1"></i> Salvar Categoria
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2 no fornecedor
    $('#fornecedor_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Digite para buscar...',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.fluxo-caixa.fornecedores.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    
    // Máscaras
    $('#fornecedor_cnpj').mask('00.000.000/0000-00');
    $('#fornecedor_telefone').mask('(00) 0000-00000');
    
    // Mostrar/ocultar campos de parcelamento
    $('#parcelado').change(function() {
        if ($(this).is(':checked')) {
            $('#numero_parcela_div, #total_parcelas_div').show();
        } else {
            $('#numero_parcela_div, #total_parcelas_div').hide();
            $('#numero_parcela, #total_parcelas').val('');
        }
    });
    
    if ($('#parcelado').is(':checked')) {
        $('#numero_parcela_div, #total_parcelas_div').show();
    }
});

// Salvar novo fornecedor
function salvarFornecedor() {
    const dados = {
        nome: $('#fornecedor_nome').val(),
        cnpj: $('#fornecedor_cnpj').val(),
        telefone: $('#fornecedor_telefone').val(),
        email: $('#fornecedor_email').val(),
        _token: '{{ csrf_token() }}'
    };
    
    if (!dados.nome) {
        toastr.error('Preencha o nome do fornecedor!');
        return;
    }
    
    $.ajax({
        url: '{{ route("admin.fluxo-caixa.fornecedores.store") }}',
        method: 'POST',
        data: dados,
        success: function(response) {
            if (response.success) {
                // Adicionar no Select2
                const newOption = new Option(response.fornecedor.text, response.fornecedor.id, true, true);
                $('#fornecedor_id').append(newOption).trigger('change');
                
                // Fechar modal e limpar
                $('#modalNovoFornecedor').modal('hide');
                $('#formNovoFornecedor')[0].reset();
                
                toastr.success(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Erro ao cadastrar fornecedor!');
        }
    });
}

// Salvar nova categoria
function salvarCategoria() {
    const dados = {
        nome: $('#categoria_nome').val(),
        tipo: 'pagar',
        cor: $('#categoria_cor').val(),
        descricao: $('#categoria_descricao').val(),
        _token: '{{ csrf_token() }}'
    };
    
    if (!dados.nome) {
        toastr.error('Preencha o nome da categoria!');
        return;
    }
    
    $.ajax({
        url: '{{ route("admin.fluxo-caixa.categorias.store") }}',
        method: 'POST',
        data: dados,
        success: function(response) {
            if (response.success) {
                // Adicionar no select
                $('#categoria_id').append(new Option(response.categoria.nome, response.categoria.id, true, true));
                
                // Fechar modal e limpar
                $('#modalNovaCategoria').modal('hide');
                $('#formNovaCategoria')[0].reset();
                
                toastr.success(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Erro ao cadastrar categoria!');
        }
    });
}
</script>
@endpush
@endsection
