@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Editar Assembleia</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assembleias.index') }}">Assembleias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Assembleia</li>
                    </ol>
                </nav>
            </div>
        </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dados da Assembleia</h5>
                        </div>
                        <div class="card-body">
                            <form id="assembleiaForm">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $assembleia->titulo }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="">Selecione...</option>
                                                <option value="ordinaria" {{ $assembleia->tipo == 'ordinaria' ? 'selected' : '' }}>Ordinária</option>
                                                <option value="extraordinaria" {{ $assembleia->tipo == 'extraordinaria' ? 'selected' : '' }}>Extraordinária</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descrição breve da assembleia...">{{ $assembleia->descricao }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="data_assembleia" class="form-label">Data <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="data_assembleia" name="data_assembleia" value="{{ $assembleia->data_assembleia->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="hora_inicio" class="form-label">Hora Início <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{ \Carbon\Carbon::parse($assembleia->hora_inicio)->format('H:i') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="hora_fim" class="form-label">Hora Fim</label>
                                            <input type="time" class="form-control" id="hora_fim" name="hora_fim" value="{{ $assembleia->hora_fim ? \Carbon\Carbon::parse($assembleia->hora_fim)->format('H:i') : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="local" class="form-label">Local <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="local" name="local" value="{{ $assembleia->local }}" required placeholder="Local onde será realizada a assembleia">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="agendada" {{ $assembleia->status == 'agendada' ? 'selected' : '' }}>Agendada</option>
                                                <option value="em_andamento" {{ $assembleia->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                                <option value="concluida" {{ $assembleia->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                                                <option value="cancelada" {{ $assembleia->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quorum_minimo" class="form-label">Quorum Mínimo</label>
                                            <input type="number" class="form-control" id="quorum_minimo" name="quorum_minimo" min="1" value="{{ $assembleia->quorum_minimo }}" placeholder="Número mínimo de presenças">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="pauta" class="form-label">Pauta</label>
                                    <textarea class="form-control" id="pauta" name="pauta" rows="4" placeholder="Itens que serão discutidos na assembleia...">{{ $assembleia->pauta }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="observacoes" class="form-label">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Observações adicionais...">{{ $assembleia->observacoes }}</textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.assembleias.index') }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line me-1"></i>Voltar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
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
    // Formulário de submissão
    $('#assembleiaForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("admin.assembleias.update", $assembleia->id) }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.assembleias.index") }}';
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erro de validação:\n';
                    
                    Object.keys(errors).forEach(function(key) {
                        errorMessage += '- ' + errors[key][0] + '\n';
                    });
                    
                    toastr.error(errorMessage);
                } else {
                    toastr.error('Erro ao atualizar assembleia');
                }
            }
        });
    });
});
</script>
@endpush
