@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Editar Conta a Pagar</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Financeiro</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fluxo-caixa.contas-pagar') }}">Contas a Pagar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Conta</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Formulário de Edição -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Editar Conta a Pagar</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.fluxo-caixa.contas-pagar.update', $conta->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Informações Básicas -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-information-line me-2"></i>Informações Básicas</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}" 
                                           id="descricao" name="descricao" value="{{ old('descricao', $conta->descricao) }}" required>
                                    @if($errors->has('descricao'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('descricao') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('categoria') ? 'is-invalid' : '' }}" 
                                            id="categoria" name="categoria" required>
                                        <option value="">Selecione...</option>
                                        @foreach($categorias as $key => $value)
                                            <option value="{{ $key }}" {{ old('categoria', $conta->categoria) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('categoria'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('categoria') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="valor" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" 
                                           id="valor" name="valor" value="{{ old('valor', $conta->valor) }}" 
                                           step="0.01" min="0" required>
                                    @if($errors->has('valor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('valor') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="data_vencimento" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control {{ $errors->has('data_vencimento') ? 'is-invalid' : '' }}" 
                                           id="data_vencimento" name="data_vencimento" 
                                           value="{{ old('data_vencimento', $conta->data_vencimento ? $conta->data_vencimento->format('Y-m-d') : '') }}" required>
                                    @if($errors->has('data_vencimento'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('data_vencimento') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="data_competencia" class="form-label">Data de Competência (Mês/Ano de Referência)</label>
                                    <input type="date" class="form-control {{ $errors->has('data_competencia') ? 'is-invalid' : '' }}" 
                                           id="data_competencia" name="data_competencia" 
                                           value="{{ old('data_competencia', $conta->data_competencia ? $conta->data_competencia->format('Y-m-d') : '') }}">
                                    @if($errors->has('data_competencia'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('data_competencia') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dados do Fornecedor -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-building-line me-2"></i>Dados do Fornecedor</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fornecedor" class="form-label">Nome do Fornecedor <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('fornecedor') ? 'is-invalid' : '' }}" 
                                           id="fornecedor" name="fornecedor" value="{{ old('fornecedor', $conta->fornecedor) }}" required>
                                    @if($errors->has('fornecedor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('fornecedor') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="cnpj_fornecedor" class="form-label">CNPJ do Fornecedor</label>
                                    <input type="text" class="form-control {{ $errors->has('cnpj_fornecedor') ? 'is-invalid' : '' }}" 
                                           id="cnpj_fornecedor" name="cnpj_fornecedor" 
                                           value="{{ old('cnpj_fornecedor', $conta->cnpj_fornecedor) }}" placeholder="00.000.000/0000-00">
                                    @if($errors->has('cnpj_fornecedor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('cnpj_fornecedor') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="telefone_fornecedor" class="form-label">Telefone do Fornecedor</label>
                                    <input type="text" class="form-control {{ $errors->has('telefone_fornecedor') ? 'is-invalid' : '' }}" 
                                           id="telefone_fornecedor" name="telefone_fornecedor" 
                                           value="{{ old('telefone_fornecedor', $conta->telefone_fornecedor) }}" placeholder="(00) 0000-0000">
                                    @if($errors->has('telefone_fornecedor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('telefone_fornecedor') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email_fornecedor" class="form-label">E-mail do Fornecedor</label>
                                    <input type="email" class="form-control {{ $errors->has('email_fornecedor') ? 'is-invalid' : '' }}" 
                                           id="email_fornecedor" name="email_fornecedor" 
                                           value="{{ old('email_fornecedor', $conta->email_fornecedor) }}" placeholder="fornecedor@exemplo.com">
                                    @if($errors->has('email_fornecedor'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('email_fornecedor') }}</div>
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
                                    <input type="text" class="form-control {{ $errors->has('numero_nota_fiscal') ? 'is-invalid' : '' }}" 
                                           id="numero_nota_fiscal" name="numero_nota_fiscal" 
                                           value="{{ old('numero_nota_fiscal', $conta->numero_nota_fiscal) }}">
                                    @if($errors->has('numero_nota_fiscal'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('numero_nota_fiscal') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="serie_nota_fiscal" class="form-label">Série da NF</label>
                                    <input type="text" class="form-control {{ $errors->has('serie_nota_fiscal') ? 'is-invalid' : '' }}" 
                                           id="serie_nota_fiscal" name="serie_nota_fiscal" 
                                           value="{{ old('serie_nota_fiscal', $conta->serie_nota_fiscal) }}">
                                    @if($errors->has('serie_nota_fiscal'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('serie_nota_fiscal') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="data_emissao_nota" class="form-label">Data de Emissão</label>
                                    <input type="date" class="form-control {{ $errors->has('data_emissao_nota') ? 'is-invalid' : '' }}" 
                                           id="data_emissao_nota" name="data_emissao_nota" 
                                           value="{{ old('data_emissao_nota', $conta->data_emissao_nota ? $conta->data_emissao_nota->format('Y-m-d') : '') }}">
                                    @if($errors->has('data_emissao_nota'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('data_emissao_nota') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="chave_acesso_nfe" class="form-label">Chave de Acesso NFe</label>
                                    <input type="text" class="form-control {{ $errors->has('chave_acesso_nfe') ? 'is-invalid' : '' }}" 
                                           id="chave_acesso_nfe" name="chave_acesso_nfe" 
                                           value="{{ old('chave_acesso_nfe', $conta->chave_acesso_nfe) }}" maxlength="44" 
                                           placeholder="44 dígitos da chave de acesso">
                                    @if($errors->has('chave_acesso_nfe'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('chave_acesso_nfe') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="arquivo_nota_fiscal" class="form-label">Anexar NF (PDF/Imagem)</label>
                                    @if($conta->arquivo_nota_fiscal)
                                        <div class="mb-2">
                                            <small class="text-muted">Arquivo atual: 
                                                <a href="{{ asset('storage/' . $conta->arquivo_nota_fiscal) }}" target="_blank">
                                                    <i class="ri-file-line"></i> Ver arquivo
                                                </a>
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control {{ $errors->has('arquivo_nota_fiscal') ? 'is-invalid' : '' }}" 
                                           id="arquivo_nota_fiscal" name="arquivo_nota_fiscal" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Máx: 5MB - Deixe em branco para manter o arquivo atual</small>
                                    @if($errors->has('arquivo_nota_fiscal'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('arquivo_nota_fiscal') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="observacoes" class="form-label">Observações</label>
                                    <textarea class="form-control {{ $errors->has('observacoes') ? 'is-invalid' : '' }}" 
                                              id="observacoes" name="observacoes" rows="3">{{ old('observacoes', $conta->observacoes) }}</textarea>
                                    @if($errors->has('observacoes'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('observacoes') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informações do Sistema -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-information-line me-2"></i>Informações do Sistema</h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge {{ $conta->status_badge_class }}">
                                            {{ $conta->status_texto }}
                                        </span>
                                    </p>
                                    <p class="mb-1"><strong>Cadastrado em:</strong> {{ $conta->created_at->format('d/m/Y H:i') }}</p>
                                    @if($conta->cadastradoPor)
                                        <p class="mb-1"><strong>Cadastrado por:</strong> {{ $conta->cadastradoPor->name }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    @if($conta->isPaga())
                                        <p class="mb-1"><strong>Data de Pagamento:</strong> {{ $conta->data_pagamento_formatada }}</p>
                                        <p class="mb-1"><strong>Forma de Pagamento:</strong> {{ $conta->forma_pagamento_texto }}</p>
                                        @if($conta->pagoPor)
                                            <p class="mb-1"><strong>Pago por:</strong> {{ $conta->pagoPor->name }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Salvar Alterações
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

@push('scripts')
<script>
$(document).ready(function() {
    // Máscara para CNPJ
    $('#cnpj_fornecedor').mask('00.000.000/0000-00');
    
    // Máscara para telefone
    $('#telefone_fornecedor').mask('(00) 0000-00000');
});
</script>
@endpush
@endsection

