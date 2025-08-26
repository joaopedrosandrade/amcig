@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Associados Aprovados</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Associados Aprovados</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Associados Aprovados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="associados_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Data Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dados serão carregados via AJAX -->
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

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializa a DataTable
    var table = $('#associados_datatable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("admin.associados.data") }}',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'cpf' },
            { data: 'tipo_associado' },
            { data: 'status' },
            { data: 'created_at' },
            { 
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

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
});
</script>
@endpush


