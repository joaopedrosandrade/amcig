@extends('layouts.associado')

@section('title', 'Meu Perfil - AMCIG')

@section('content')
<div class="main-content">
    <div class="page-content">
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
            </div>
            <!-- end page title -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dados Pessoais</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('associado.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                        <small class="text-muted">O email não pode ser alterado</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}" required>
                                        @error('telefone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="tipo_associado" class="form-label">Tipo de Associado</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->tipo_associado) }}" readonly>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h6 class="text-primary mb-3">Endereço</h6>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep', $user->cep) }}" required>
                                        @error('cep')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="logradouro" class="form-label">Logradouro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('logradouro') is-invalid @enderror" id="logradouro" name="logradouro" value="{{ old('logradouro', $user->logradouro) }}" required>
                                        @error('logradouro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $user->numero) }}" required>
                                        @error('numero')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" value="{{ old('complemento', $user->complemento) }}">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro', $user->bairro) }}" required>
                                        @error('bairro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-2 mb-3">
                                        <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" value="{{ $user->cidade }}" readonly>
                                    </div>
                                    
                                    <div class="col-md-2 mb-3">
                                        <label for="uf" class="form-label">UF <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="uf" name="uf" value="{{ $user->uf }}" readonly>
                                    </div>
                                </div>

                                @if($user->tipo_associado === 'comerciante')
                                    <hr class="my-4">
                                    <h6 class="text-primary mb-3">Informações do Comércio</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nome_comercio" class="form-label">Nome do Comércio <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nome_comercio') is-invalid @enderror" id="nome_comercio" name="nome_comercio" value="{{ old('nome_comercio', $user->nome_comercio) }}" required>
                                            @error('nome_comercio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="ramo_atividade" class="form-label">Ramo de Atividade <span class="text-danger">*</span></label>
                                            <select class="form-select @error('ramo_atividade') is-invalid @enderror" id="ramo_atividade" name="ramo_atividade" required>
                                                <option value="">Selecione o ramo</option>
                                                <option value="alimentacao" {{ old('ramo_atividade', $user->ramo_atividade) === 'alimentacao' ? 'selected' : '' }}>Alimentação</option>
                                                <option value="varejo" {{ old('ramo_atividade', $user->ramo_atividade) === 'varejo' ? 'selected' : '' }}>Varejo</option>
                                                <option value="servicos" {{ old('ramo_atividade', $user->ramo_atividade) === 'servicos' ? 'selected' : '' }}>Serviços</option>
                                                <option value="construcao" {{ old('ramo_atividade', $user->ramo_atividade) === 'construcao' ? 'selected' : '' }}>Construção</option>
                                                <option value="transporte" {{ old('ramo_atividade', $user->ramo_atividade) === 'transporte' ? 'selected' : '' }}>Transporte</option>
                                                <option value="saude" {{ old('ramo_atividade', $user->ramo_atividade) === 'saude' ? 'selected' : '' }}>Saúde</option>
                                                <option value="educacao" {{ old('ramo_atividade', $user->ramo_atividade) === 'educacao' ? 'selected' : '' }}>Educação</option>
                                                <option value="lazer" {{ old('ramo_atividade', $user->ramo_atividade) === 'lazer' ? 'selected' : '' }}>Lazer e Turismo</option>
                                                <option value="outros" {{ old('ramo_atividade', $user->ramo_atividade) === 'outros' ? 'selected' : '' }}>Outros</option>
                                            </select>
                                            @error('ramo_atividade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="endereco_comercio" class="form-label">Endereço do Comércio <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('endereco_comercio') is-invalid @enderror" id="endereco_comercio" name="endereco_comercio" rows="3" required>{{ old('endereco_comercio', $user->endereco_comercio) }}</textarea>
                                            @error('endereco_comercio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-2"></i>Salvar Alterações
                                        </button>
                                        <a href="{{ route('associado.dashboard') }}" class="btn btn-secondary ms-2">
                                            <i class="ri-arrow-left-line me-2"></i>Voltar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/imask"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    if (typeof IMask !== 'undefined') {
        IMask(document.getElementById('telefone'), {
            mask: '(00) 00000-0000'
        });
        
        // Máscara para CEP
        IMask(document.getElementById('cep'), {
            mask: '00000-000'
        });
    }
});
</script>
@endpush
