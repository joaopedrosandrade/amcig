@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Assembleias</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assembleias</li>
                    </ol>
                </nav>
            </div>
        </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Lista de Assembleias</h5>
                                <a href="{{ route('admin.assembleias.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-1"></i>Nova Assembleia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="assembleiasTable">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Data</th>
                                            <th>Horário</th>
                                            <th>Local</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Presenças</th>
                                            <th>Lista Ativa</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assembleias as $assembleia)
                                            <tr>
                                                <td>
                                                    <strong>{{ $assembleia->titulo }}</strong>
                                                    @if($assembleia->descricao)
                                                        <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($assembleia->descricao, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $assembleia->data_assembleia->format('d/m/Y') }}</td>
                                                <td>
                                                    {{ $assembleia->hora_inicio }}
                                                    @if($assembleia->hora_fim)
                                                        - {{ $assembleia->hora_fim }}
                                                    @endif
                                                </td>
                                                <td>{{ $assembleia->local }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $assembleia->tipo == 'ordinaria' ? 'primary' : 'warning' }}">
                                                        {{ ucfirst($assembleia->tipo) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $assembleia->status == 'agendada' ? 'info' : ($assembleia->status == 'em_andamento' ? 'warning' : ($assembleia->status == 'concluida' ? 'success' : 'danger')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $assembleia->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $assembleia->total_presencas }}</span>
                                                    @if($assembleia->quorum_minimo)
                                                        / {{ $assembleia->quorum_minimo }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-lista" 
                                                               type="checkbox" 
                                                               data-id="{{ $assembleia->id }}"
                                                               {{ $assembleia->lista_presenca_ativa ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            Ações
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="{{ route('admin.assembleias.show', $assembleia->id) }}">
                                                                <i class="ri-eye-line me-2"></i>Ver Detalhes
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.assembleias.edit', $assembleia->id) }}">
                                                                <i class="ri-edit-line me-2"></i>Editar
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item" href="#" onclick="gerarLink({{ $assembleia->id }})">
                                                                <i class="ri-link me-2"></i>Gerar Link
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.assembleias.presencas', $assembleia->id) }}">
                                                                <i class="ri-list-check me-2"></i>Lista Presenças
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.assembleias.exportar-presencas', $assembleia->id) }}">
                                                                <i class="ri-download-line me-2"></i>Exportar CSV
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="excluirAssembleia({{ $assembleia->id }})">
                                                                <i class="ri-delete-bin-line me-2"></i>Excluir
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                        <button class="btn btn-outline-secondary" type="button" onclick="copiarLink()">
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
$(document).ready(function() {
    $('#assembleiasTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        order: [[1, 'desc']] // Ordenar por data
    });

    // Toggle lista de presença
    $('.toggle-lista').on('change', function() {
        const id = $(this).data('id');
        const ativo = $(this).is(':checked');
        
        $.ajax({
            url: `{{ url('assembleias') }}/${id}/toggle-lista`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Erro ao alterar status da lista de presença');
                location.reload();
            }
        });
    });
});

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

function copiarLink() {
    const linkInput = document.getElementById('linkInput');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    toastr.success('Link copiado para a área de transferência!');
}

function excluirAssembleia(id) {
    if (confirm('Tem certeza que deseja excluir esta assembleia?')) {
        $.ajax({
            url: `{{ url('assembleias') }}/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            },
            error: function() {
                toastr.error('Erro ao excluir assembleia');
            }
        });
    }
}
</script>
@endpush
