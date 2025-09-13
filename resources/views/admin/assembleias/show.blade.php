@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">{{ $assembleia->titulo }}</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assembleias.index') }}">Assembleias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
            </div>
        </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Informações da Assembleia</h5>
                                <div>
                                    <span class="badge bg-{{ $assembleia->tipo == 'ordinaria' ? 'primary' : 'warning' }} fs-12">
                                        {{ ucfirst($assembleia->tipo) }}
                                    </span>
                                    <span class="badge bg-{{ $assembleia->status == 'agendada' ? 'info' : ($assembleia->status == 'em_andamento' ? 'warning' : ($assembleia->status == 'concluida' ? 'success' : 'danger')) }} fs-12">
                                        {{ ucfirst(str_replace('_', ' ', $assembleia->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Data e Horário</h6>
                                    <p class="mb-3">
                                        <i class="ri-calendar-line me-2"></i>
                                        {{ $assembleia->data_assembleia->format('d/m/Y') }} às {{ $assembleia->hora_inicio }}
                                        @if($assembleia->hora_fim)
                                            - {{ $assembleia->hora_fim }}
                                        @endif
                                    </p>

                                    <h6 class="text-muted">Local</h6>
                                    <p class="mb-3">
                                        <i class="ri-map-pin-line me-2"></i>
                                        {{ $assembleia->local }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($assembleia->quorum_minimo)
                                        <h6 class="text-muted">Quorum</h6>
                                        <p class="mb-3">
                                            <i class="ri-group-line me-2"></i>
                                            {{ $assembleia->total_presencas }} / {{ $assembleia->quorum_minimo }} presenças
                                            @if($assembleia->atingiuQuorum())
                                                <span class="badge bg-success ms-2">Quorum Atingido</span>
                                            @else
                                                <span class="badge bg-warning ms-2">Quorum Pendente</span>
                                            @endif
                                        </p>
                                    @endif

                                    <h6 class="text-muted">Lista de Presença</h6>
                                    <p class="mb-3">
                                        <i class="ri-checkbox-circle-line me-2"></i>
                                        @if($assembleia->lista_presenca_ativa)
                                            <span class="text-success">Ativa</span>
                                        @else
                                            <span class="text-muted">Inativa</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($assembleia->descricao)
                                <h6 class="text-muted">Descrição</h6>
                                <p class="mb-3">{{ $assembleia->descricao }}</p>
                            @endif

                            @if($assembleia->pauta)
                                <h6 class="text-muted">Pauta</h6>
                                <div class="bg-light p-3 rounded mb-3">
                                    {!! nl2br(e($assembleia->pauta)) !!}
                                </div>
                            @endif

                            @if($assembleia->observacoes)
                                <h6 class="text-muted">Observações</h6>
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($assembleia->observacoes)) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ações</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.assembleias.edit', $assembleia->id) }}" class="btn btn-primary">
                                    <i class="ri-edit-line me-2"></i>Editar Assembleia
                                </a>

                                <button type="button" class="btn btn-success" onclick="gerarLink({{ $assembleia->id }})">
                                    <i class="ri-link me-2"></i>Gerar Link Presença
                                </button>

                                <a href="{{ route('admin.assembleias.presencas', $assembleia->id) }}" class="btn btn-info">
                                    <i class="ri-list-check me-2"></i>Ver Presenças
                                </a>

                                <a href="{{ route('admin.assembleias.exportar-presencas', $assembleia->id) }}" class="btn btn-warning">
                                    <i class="ri-download-line me-2"></i>Exportar CSV
                                </a>

                                <hr>

                                <button type="button" class="btn btn-{{ $assembleia->lista_presenca_ativa ? 'danger' : 'success' }}" onclick="toggleLista({{ $assembleia->id }})">
                                    <i class="ri-{{ $assembleia->lista_presenca_ativa ? 'close' : 'check' }}-circle-line me-2"></i>
                                    {{ $assembleia->lista_presenca_ativa ? 'Desativar' : 'Ativar' }} Lista
                                </button>

                                <a href="{{ route('admin.assembleias.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line me-2"></i>Voltar
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($assembleia->link_presenca)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Link de Presença</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ route('assembleia.presenca', $assembleia->link_presenca) }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copiarLink('{{ route('assembleia.presenca', $assembleia->link_presenca) }}')">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    Compartilhe este link para que os associados registrem sua presença.
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal para exibir link -->
<div class="modal fade" id="linkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Link da Lista de Presença</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Link para compartilhar:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="linkInput" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copiarLinkModal()">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    Compartilhe este link com os associados para que possam registrar sua presença.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function gerarLink(id) {
    const url = `{{ url('assembleias') }}/${id}/gerar-link`;
    console.log('Gerando link para assembleia ID:', id);
    console.log('URL da requisição:', url);
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Resposta recebida:', response);
            if (response.success) {
                $('#linkInput').val(response.link);
                $('#linkModal').modal('show');
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            console.log('Erro na requisição:', xhr);
            console.log('Status:', status);
            console.log('Error:', error);
            console.log('Response Text:', xhr.responseText);
            toastr.error('Erro ao gerar link: ' + error);
        }
    });
}

function copiarLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        toastr.success('Link copiado para a área de transferência!');
    }, function() {
        toastr.error('Erro ao copiar link');
    });
}

function copiarLinkModal() {
    const linkInput = document.getElementById('linkInput');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    toastr.success('Link copiado para a área de transferência!');
}

function toggleLista(id) {
        $.ajax({
            url: `{{ url('assembleias') }}/${id}/toggle-lista`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function() {
            toastr.error('Erro ao alterar status da lista de presença');
        }
    });
}
</script>
@endpush
