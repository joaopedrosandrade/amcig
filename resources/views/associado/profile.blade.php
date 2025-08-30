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
                                    <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento', $user->complemento) }}">
                                    @error('complemento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro', $user->bairro) }}" required>
                                    @error('bairro')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade', $user->cidade) }}" required>
                                    @error('cidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="uf" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select @error('uf') is-invalid @enderror" id="uf" name="uf" required>
                                        <option value="">Selecione o estado</option>
                                        <option value="AC" {{ old('uf', $user->uf) == 'AC' ? 'selected' : '' }}>Acre</option>
                                        <option value="AL" {{ old('uf', $user->uf) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                        <option value="AP" {{ old('uf', $user->uf) == 'AP' ? 'selected' : '' }}>Amapá</option>
                                        <option value="AM" {{ old('uf', $user->uf) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                        <option value="BA" {{ old('uf', $user->uf) == 'BA' ? 'selected' : '' }}>Bahia</option>
                                        <option value="CE" {{ old('uf', $user->uf) == 'CE' ? 'selected' : '' }}>Ceará</option>
                                        <option value="DF" {{ old('uf', $user->uf) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                        <option value="ES" {{ old('uf', $user->uf) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                        <option value="GO" {{ old('uf', $user->uf) == 'GO' ? 'selected' : '' }}>Goiás</option>
                                        <option value="MA" {{ old('uf', $user->uf) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                        <option value="MT" {{ old('uf', $user->uf) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                        <option value="MS" {{ old('uf', $user->uf) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                        <option value="MG" {{ old('uf', $user->uf) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                        <option value="PA" {{ old('uf', $user->uf) == 'PA' ? 'selected' : '' }}>Pará</option>
                                        <option value="PB" {{ old('uf', $user->uf) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                        <option value="PR" {{ old('uf', $user->uf) == 'PR' ? 'selected' : '' }}>Paraná</option>
                                        <option value="PE" {{ old('uf', $user->uf) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                        <option value="PI" {{ old('uf', $user->uf) == 'PI' ? 'selected' : '' }}>Piauí</option>
                                        <option value="RJ" {{ old('uf', $user->uf) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                        <option value="RN" {{ old('uf', $user->uf) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                        <option value="RS" {{ old('uf', $user->uf) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                        <option value="RO" {{ old('uf', $user->uf) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                        <option value="RR" {{ old('uf', $user->uf) == 'RR' ? 'selected' : '' }}>Roraima</option>
                                        <option value="SC" {{ old('uf', $user->uf) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                        <option value="SP" {{ old('uf', $user->uf) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                        <option value="SE" {{ old('uf', $user->uf) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                        <option value="TO" {{ old('uf', $user->uf) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                    </select>
                                    @error('uf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" value="{{ $user->cpf ?? 'N/A' }}" readonly>
                                    <small class="text-muted">O CPF não pode ser alterado</small>
                                </div>
                            </div>

                            @if($user->isComerciante())
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
                                            <option value="alimentacao" {{ old('ramo_atividade', $user->ramo_atividade) == 'alimentacao' ? 'selected' : '' }}>Alimentação</option>
                                            <option value="varejo" {{ old('ramo_atividade', $user->ramo_atividade) == 'varejo' ? 'selected' : '' }}>Varejo</option>
                                            <option value="servicos" {{ old('ramo_atividade', $user->ramo_atividade) == 'servicos' ? 'selected' : '' }}>Serviços</option>
                                            <option value="saude" {{ old('ramo_atividade', $user->ramo_atividade) == 'saude' ? 'selected' : '' }}>Saúde</option>
                                            <option value="educacao" {{ old('ramo_atividade', $user->ramo_atividade) == 'educacao' ? 'selected' : '' }}>Educação</option>
                                            <option value="outros" {{ old('ramo_atividade', $user->ramo_atividade) == 'outros' ? 'selected' : '' }}>Outros</option>
                                        </select>
                                        @error('ramo_atividade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="endereco_comercio" class="form-label">Endereço do Comércio <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('endereco_comercio') is-invalid @enderror" id="endereco_comercio" name="endereco_comercio" value="{{ old('endereco_comercio', $user->endereco_comercio) }}" required>
                                        @error('endereco_comercio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Máscara para telefone
    $('#telefone').mask('(00) 00000-0000');
    
    // Máscara para CEP
    $('#cep').mask('00000-000');
    
    // Busca de endereço por CEP
    $('#cep').on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                if (!data.erro) {
                    $('#logradouro').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#uf').val(data.uf);
                }
            });
        }
    });
});
</script>
@endpush
