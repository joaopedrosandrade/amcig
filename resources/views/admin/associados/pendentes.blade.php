@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Associados Pendentes</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.associados.index') }}">Associados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pendentes</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Associados Aguardando Aprovação</h5>
                        <p class="text-muted mb-0">Gerencie as solicitações de novos associados</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="associados_pendentes_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Matrícula</th>
                                        <th>CPF</th>
                                        <th>Tipo</th>
                                        <th>Data Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($associadosPendentes as $associado)
                                        @php
                                            $tipos = [
                                                'morador' => 'Morador',
                                                'comerciante' => 'Comerciante'
                                            ];
                                        @endphp
                                        <tr>
                                            <td>{{ $associado->id }}</td>
                                            <td>{{ $associado->name }}</td>
                                            <td>{{ $associado->matricula ?? 'N/A' }}</td>
                                            <td>{{ $associado->cpf ?? 'N/A' }}</td>
                                            <td>{{ $tipos[$associado->tipo_associado] ?? 'N/A' }}</td>
                                            <td>{{ $associado->created_at_formatted }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-info view-associado" data-id="{{ $associado->id }}" title="Visualizar">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success aprovar-associado" data-id="{{ $associado->id }}" title="Aprovar">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger rejeitar-associado" data-id="{{ $associado->id }}" title="Rejeitar">
                                                        <i class="ri-close-line"></i>
                                                    </button>
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
</main>

<!-- Modal para visualizar detalhes do associado -->
<div class="modal fade" id="associadoModal" tabindex="-1" aria-labelledby="associadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="associadoModalLabel">Detalhes do Associado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="associadoModalBody">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar aprovação -->
<div class="modal fade" id="aprovarModal" tabindex="-1" aria-labelledby="aprovarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aprovarModalLabel">Confirmar Aprovação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja aprovar este associado?</p>
                <p class="text-muted">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarAprovacao">Confirmar Aprovação</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar rejeição -->
<div class="modal fade" id="rejeitarModal" tabindex="-1" aria-labelledby="rejeitarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejeitarModalLabel">Confirmar Rejeição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja rejeitar este associado?</p>
                <div class="mb-3">
                    <label for="motivoRejeicao" class="form-label">Motivo da rejeição (opcional):</label>
                    <textarea class="form-control" id="motivoRejeicao" rows="3" placeholder="Digite o motivo da rejeição..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarRejeicao">Confirmar Rejeição</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializa a DataTable
    var table = $('#associados_pendentes_datatable').DataTable({
        processing: true,
        serverSide: false,
        order: [[0, 'desc']],
        language: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Clique em uma linha para selecioná-la",
                    "1": "Selecionado 1 linha"
                }
            },
            "buttons": {
                "copy": "Copiar",
                "copyTitle": "Cópia bem sucedida",
                "copySuccess": {
                    "1": "Uma linha copiada com sucesso",
                    "_": "%d linhas copiadas com sucesso"
                },
                "print": "Imprimir",
                "csv": "CSV",
                "excel": "Excel",
                "pdf": "PDF"
            }
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
    });

    var associadoIdParaAprovar = null;
    var associadoIdParaRejeitar = null;

    // Evento para visualizar detalhes do associado
    $(document).on('click', '.view-associado', function() {
        var associadoId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.associados.show") }}',
            type: 'GET',
            data: { id: associadoId },
            success: function(response) {
                $('#associadoModalBody').html(response);
                $('#associadoModal').modal('show');
            },
            error: function() {
                alert('Erro ao carregar detalhes do associado.');
            }
        });
    });

    // Evento para aprovar associado
    $(document).on('click', '.aprovar-associado', function() {
        associadoIdParaAprovar = $(this).data('id');
        $('#aprovarModal').modal('show');
    });

    // Evento para rejeitar associado
    $(document).on('click', '.rejeitar-associado', function() {
        associadoIdParaRejeitar = $(this).data('id');
        $('#motivoRejeicao').val(''); // Limpa o campo
        $('#rejeitarModal').modal('show');
    });

    // Confirmar aprovação
    $('#confirmarAprovacao').click(function() {
        if (associadoIdParaAprovar) {
            $.ajax({
                url: '{{ route("admin.associados.aprovar") }}',
                type: 'POST',
                data: {
                    id: associadoIdParaAprovar,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#aprovarModal').modal('hide');
                        // Recarrega a página para atualizar a tabela
                        location.reload();
                    } else {
                        alert('Erro: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erro ao aprovar associado.');
                }
            });
        }
    });

    // Confirmar rejeição
    $('#confirmarRejeicao').click(function() {
        if (associadoIdParaRejeitar) {
            $.ajax({
                url: '{{ route("admin.associados.rejeitar") }}',
                type: 'POST',
                data: {
                    id: associadoIdParaRejeitar,
                    motivo: $('#motivoRejeicao').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#rejeitarModal').modal('hide');
                        // Recarrega a página para atualizar a tabela
                        location.reload();
                    } else {
                        alert('Erro: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erro ao rejeitar associado.');
                }
            });
        }
    });
});
</script>
@endpush
