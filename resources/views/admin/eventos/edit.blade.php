@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Editar Evento</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.eventos.index') }}">Eventos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Evento</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Editar Dados do Evento</h5>
                    </div>
                    <div class="card-body">
                        <form id="eventoForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $evento->titulo }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="">Selecione...</option>
                                            <option value="assembleia" {{ $evento->tipo == 'assembleia' ? 'selected' : '' }}>Assembleia</option>
                                            <option value="reuniao" {{ $evento->tipo == 'reuniao' ? 'selected' : '' }}>Reunião</option>
                                            <option value="palestra" {{ $evento->tipo == 'palestra' ? 'selected' : '' }}>Palestra</option>
                                            <option value="workshop" {{ $evento->tipo == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                            <option value="outro" {{ $evento->tipo == 'outro' ? 'selected' : '' }}>Outro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ $evento->descricao }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="data_evento" class="form-label">Data <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data_evento" name="data_evento" value="{{ $evento->data_evento->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="hora_inicio" class="form-label">Hora Início <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{ $evento->hora_inicio }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="hora_fim" class="form-label">Hora Fim</label>
                                        <input type="time" class="form-control" id="hora_fim" name="hora_fim" value="{{ $evento->hora_fim }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="agendado" {{ $evento->status == 'agendado' ? 'selected' : '' }}>Agendado</option>
                                            <option value="em_andamento" {{ $evento->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                            <option value="concluido" {{ $evento->status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                            <option value="cancelado" {{ $evento->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="local" class="form-label">Local <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="local" name="local" value="{{ $evento->local }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pauta" class="form-label">Pauta</label>
                                        <textarea class="form-control" id="pauta" name="pauta" rows="5">{{ $evento->pauta }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="5">{{ $evento->observacoes }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="quorum_minimo" class="form-label">Quórum Mínimo (opcional)</label>
                                <input type="number" class="form-control" id="quorum_minimo" name="quorum_minimo" value="{{ $evento->quorum_minimo }}" min="1">
                            </div>

                            <div class="text-end">
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
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Formulário de submissão
    $('#eventoForm').on('submit', function(e) {
        e.preventDefault();
        
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        // Desabilitar botão e mostrar loading
        $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Salvando...');
        
        const formData = $(this).serialize();
        
        console.log('Enviando dados:', formData);
        console.log('URL:', '{{ route("admin.eventos.update", $evento->id) }}');
        
        $.ajax({
            url: '{{ route("admin.eventos.update", $evento->id) }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                console.log('Resposta recebida:', response);
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.eventos.index") }}';
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Erro ao atualizar evento');
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
                    const errorMsg = xhr.responseJSON?.message || 'Erro ao atualizar evento';
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
