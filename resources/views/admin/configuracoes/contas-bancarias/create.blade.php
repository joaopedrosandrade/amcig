@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Nova Conta Bancária</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Configurações</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contas-bancarias.index') }}">Contas Bancárias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nova Conta</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cadastrar Conta Bancária</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contas-bancarias.store') }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="ri-bank-line me-2"></i>Informações da Conta</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome da Conta <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}" 
                                           name="nome" value="{{ old('nome') }}" required 
                                           placeholder="Ex: Caixa Econômica - Conta Corrente">
                                    @if($errors->has('nome'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('nome') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Banco <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('banco') ? 'is-invalid' : '' }}" 
                                           name="banco" value="{{ old('banco') }}" required 
                                           placeholder="Ex: Caixa Econômica Federal">
                                    @if($errors->has('banco'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('banco') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tipo de Conta <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('tipo_conta') ? 'is-invalid' : '' }}" 
                                            name="tipo_conta" required>
                                        <option value="">Selecione...</option>
                                        <option value="corrente" {{ old('tipo_conta') == 'corrente' ? 'selected' : '' }}>Conta Corrente</option>
                                        <option value="poupanca" {{ old('tipo_conta') == 'poupanca' ? 'selected' : '' }}>Poupança</option>
                                        <option value="aplicacao" {{ old('tipo_conta') == 'aplicacao' ? 'selected' : '' }}>Aplicação</option>
                                        <option value="caixa" {{ old('tipo_conta') == 'caixa' ? 'selected' : '' }}>Caixa</option>
                                    </select>
                                    @if($errors->has('tipo_conta'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('tipo_conta') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Agência</label>
                                    <input type="text" class="form-control" name="agencia" value="{{ old('agencia') }}" placeholder="0000">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Número da Conta</label>
                                    <input type="text" class="form-control" name="numero_conta" value="{{ old('numero_conta') }}" placeholder="00000-0">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Saldo Inicial <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control {{ $errors->has('saldo_inicial') ? 'is-invalid' : '' }}" 
                                           name="saldo_inicial" value="{{ old('saldo_inicial', '0') }}" 
                                           step="0.01" required>
                                    @if($errors->has('saldo_inicial'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('saldo_inicial') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Titular da Conta</label>
                                    <input type="text" class="form-control" name="titular" value="{{ old('titular') }}" placeholder="Nome do titular">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CPF/CNPJ do Titular</label>
                                    <input type="text" class="form-control" name="cpf_cnpj_titular" value="{{ old('cpf_cnpj_titular') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="principal" value="1" {{ old('principal') ? 'checked' : '' }}>
                                        <label class="form-check-label">Definir como conta principal</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}>
                                        <label class="form-check-label">Conta ativa</label>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Cadastrar Conta
                                    </button>
                                    <a href="{{ route('admin.contas-bancarias.index') }}" class="btn btn-secondary">
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
@endsection

