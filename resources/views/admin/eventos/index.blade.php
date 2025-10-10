@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Eventos</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Eventos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                Lista de Eventos
                                <small class="text-muted">({{ $eventos->count() }} evento{{ $eventos->count() != 1 ? 's' : '' }})</small>
                            </h5>
                            <a href="{{ route('admin.eventos.create') }}" class="btn btn-success btn-sm">
                                <i class="ri-add-line me-1"></i>Novo Evento
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Título</th>
                                        <th>Data e Horário</th>
                                        <th>Local</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th class="text-center">Lista</th>
                                        <th class="text-center">Presenças</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($eventos as $evento)
                                        <tr>
                                            <td>
                                                <strong>{{ $evento->titulo }}</strong>
                                                @if($evento->descricao)
                                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($evento->descricao, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $evento->data_evento->format('d/m/Y') }}</strong>
                                                <br><small class="text-muted">
                                                    {{ $evento->hora_inicio }}
                                                    @if($evento->hora_fim)
                                                        - {{ $evento->hora_fim }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $evento->local }}</td>
                                            <td>
                                                @php
                                                    $tipos = [
                                                        'assembleia' => ['badge' => 'primary', 'text' => 'Assembleia'],
                                                        'reuniao' => ['badge' => 'info', 'text' => 'Reunião'],
                                                        'palestra' => ['badge' => 'warning', 'text' => 'Palestra'],
                                                        'workshop' => ['badge' => 'success', 'text' => 'Workshop'],
                                                        'outro' => ['badge' => 'secondary', 'text' => 'Outro']
                                                    ];
                                                    $tipo = $tipos[$evento->tipo] ?? ['badge' => 'secondary', 'text' => ucfirst($evento->tipo)];
                                                @endphp
                                                <span class="badge bg-{{ $tipo['badge'] }}">
                                                    {{ $tipo['text'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $status = [
                                                        'agendado' => ['badge' => 'info', 'text' => 'Agendado'],
                                                        'em_andamento' => ['badge' => 'warning', 'text' => 'Em Andamento'],
                                                        'concluido' => ['badge' => 'success', 'text' => 'Concluído'],
                                                        'cancelado' => ['badge' => 'danger', 'text' => 'Cancelado']
                                                    ];
                                                    $statusInfo = $status[$evento->status] ?? ['badge' => 'secondary', 'text' => ucfirst($evento->status)];
                                                @endphp
                                                <span class="badge bg-{{ $statusInfo['badge'] }}">
                                                    {{ $statusInfo['text'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input toggle-lista" type="checkbox" role="switch"
                                                           data-id="{{ $evento->id }}"
                                                           {{ $evento->lista_presenca_ativa ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.eventos.presencas', $evento->id) }}" class="btn btn-sm btn-outline-info" title="Ver Presenças">
                                                    <i class="ri-user-line"></i> {{ $evento->total_presencas }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.eventos.show', $evento->id) }}" 
                                                       class="btn btn-outline-info" title="Visualizar">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('admin.eventos.edit', $evento->id) }}" 
                                                       class="btn btn-outline-primary" title="Editar">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            title="Gerar Link de Presença" 
                                                            onclick="gerarLink({{ $evento->id }})">
                                                        <i class="ri-link"></i>
                                                    </button>
                                                    <a href="{{ route('admin.eventos.presencas', $evento->id) }}" 
                                                       class="btn btn-outline-secondary" title="Ver Lista de Presenças">
                                                        <i class="ri-group-line"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            title="Excluir" 
                                                            onclick="excluirEvento({{ $evento->id }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-5">
                                                <i class="ri-calendar-event-line" style="font-size: 3rem;"></i>
                                                <p class="mb-0 mt-2">Nenhum evento cadastrado</p>
                                                <small class="text-muted">Clique em "Novo Evento" para adicionar</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                    Compartilhe este link com os participantes para que possam registrar sua presença.
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle lista de presença
    $('.toggle-lista').on('change', function() {
        const id = $(this).data('id');
        const ativo = $(this).is(':checked');
        
        $.ajax({
            url: `{{ url('admin/eventos') }}/${id}/toggle-lista`,
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
    const url = `{{ url('admin/eventos') }}/${id}/gerar-link`;
    console.log('Gerando link para evento ID:', id);
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

function excluirEvento(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ url('admin/eventos') }}/${id}`,
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
                    toastr.error('Erro ao excluir evento');
                }
            });
        }
    });
}
</script>
@endpush
