@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Relatórios de Associados</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.associados.index') }}">Associados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-search-line me-2"></i>Filtros de Busca
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formFiltros">
                            <div class="row">
                                <!-- Sexo -->
                                <div class="col-md-3 mb-3">
                                    <label for="sexo" class="form-label">Sexo</label>
                                    <select class="form-select" id="sexo" name="sexo">
                                        <option value="">Todos</option>
                                        @foreach($sexos as $sexo)
                                            <option value="{{ $sexo }}">{{ ucfirst($sexo) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Bairro -->
                                <div class="col-md-3 mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <select class="form-select" id="bairro" name="bairro">
                                        <option value="">Todos</option>
                                        @foreach($bairros as $bairro)
                                            <option value="{{ $bairro }}">{{ $bairro }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Logradouro (Rua) -->
                                <div class="col-md-3 mb-3">
                                    <label for="logradouro" class="form-label">Rua</label>
                                    <select class="form-select" id="logradouro" name="logradouro">
                                        <option value="">Todas</option>
                                        @foreach($logradouros as $logradouro)
                                            <option value="{{ $logradouro }}">{{ $logradouro }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de Associado -->
                                <div class="col-md-3 mb-3">
                                    <label for="tipo_associado" class="form-label">Tipo</label>
                                    <select class="form-select" id="tipo_associado" name="tipo_associado">
                                        <option value="">Todos</option>
                                        <option value="morador">Morador</option>
                                        <option value="comerciante">Comerciante</option>
                                        <option value="ambos">Ambos</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Idade Mínima -->
                                <div class="col-md-3 mb-3">
                                    <label for="idade_min" class="form-label">Idade Mínima</label>
                                    <input type="number" class="form-control" id="idade_min" name="idade_min" min="0" max="120" placeholder="Ex: 18">
                                </div>

                                <!-- Idade Máxima -->
                                <div class="col-md-3 mb-3">
                                    <label for="idade_max" class="form-label">Idade Máxima</label>
                                    <input type="number" class="form-control" id="idade_max" name="idade_max" min="0" max="120" placeholder="Ex: 65">
                                </div>

                                <!-- Status -->
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="aprovado">Aprovado</option>
                                        <option value="rejeitado">Rejeitado</option>
                                        <option value="desativado">Desativado</option>
                                    </select>
                                </div>

                                <!-- Data de Cadastro -->
                                <div class="col-md-3 mb-3">
                                    <label for="data_cadastro_inicio" class="form-label">Data Cadastro (Início)</label>
                                    <input type="date" class="form-control" id="data_cadastro_inicio" name="data_cadastro_inicio">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="data_cadastro_fim" class="form-label">Data Cadastro (Fim)</label>
                                    <input type="date" class="form-control" id="data_cadastro_fim" name="data_cadastro_fim">
                                </div>

                                <div class="col-md-9 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="ri-search-line me-1"></i>Buscar
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="limparFiltros">
                                        <i class="ri-refresh-line me-1"></i>Limpar
                                    </button>
                                    <button type="button" class="btn btn-success ms-2" id="exportarExcel">
                                        <i class="ri-file-excel-line me-1"></i>Exportar Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4" id="estatisticas" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-bar-chart-line me-2"></i>Estatísticas dos Resultados
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="estatisticasContent">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check me-2"></i>Resultados da Busca
                            <span class="badge bg-primary ms-2" id="totalResultados" style="display: none;">0</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaResultados" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Matrícula</th>
                                        <th>CPF</th>
                                        <th>Idade</th>
                                        <th>Sexo</th>
                                        <th>Bairro</th>
                                        <th>Rua</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Data Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="corpoTabela">
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">
                                            <i class="ri-search-line me-2"></i>Use os filtros acima para buscar associados
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    // Buscar associados
    $('#formFiltros').on('submit', function(e) {
        e.preventDefault();
        buscarAssociados();
    });

    // Limpar filtros
    $('#limparFiltros').on('click', function() {
        $('#formFiltros')[0].reset();
        $('#corpoTabela').html(`
            <tr>
                <td colspan="12" class="text-center text-muted py-4">
                    <i class="ri-search-line me-2"></i>Use os filtros acima para buscar associados
                </td>
            </tr>
        `);
        $('#estatisticas').hide();
        $('#totalResultados').hide();
    });

    // Exportar Excel
    $('#exportarExcel').on('click', function() {
        if ($('#totalResultados').text() === '0' || $('#totalResultados').is(':hidden')) {
            alert('Nenhum resultado para exportar. Faça uma busca primeiro.');
            return;
        }
        
        // Implementar exportação Excel aqui
        alert('Funcionalidade de exportação será implementada em breve.');
    });

    function buscarAssociados() {
        const formData = $('#formFiltros').serialize();
        
        $.ajax({
            url: '{{ route("admin.associados.relatorios.buscar") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#corpoTabela').html(`
                    <tr>
                        <td colspan="12" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                if (response.success) {
                    exibirResultados(response.associados);
                    exibirEstatisticas(response.estatisticas);
                } else {
                    alert('Erro ao buscar associados: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Erro:', xhr);
                alert('Erro ao buscar associados. Tente novamente.');
            }
        });
    }

    function exibirResultados(associados) {
        let html = '';
        
        if (associados.length === 0) {
            html = `
                <tr>
                    <td colspan="12" class="text-center text-muted py-4">
                        <i class="ri-search-line me-2"></i>Nenhum associado encontrado com os filtros aplicados
                    </td>
                </tr>
            `;
        } else {
            associados.forEach(function(associado) {
                const foto = associado.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(associado.name) + '&background=6366f1&color=ffffff&size=40';
                const idade = associado.idade || 'N/A';
                const tipoAssociado = {
                    'morador': 'Morador',
                    'comerciante': 'Comerciante',
                    'ambos': 'Ambos'
                }[associado.tipo_associado] || 'N/A';
                
                const statusBadge = {
                    'pendente': '<span class="badge bg-warning">Pendente</span>',
                    'aprovado': '<span class="badge bg-success">Aprovado</span>',
                    'rejeitado': '<span class="badge bg-danger">Rejeitado</span>',
                    'desativado': '<span class="badge bg-secondary">Desativado</span>'
                }[associado.status] || '<span class="badge bg-light text-dark">N/A</span>';

                html += `
                    <tr>
                        <td>
                            <div class="avatar-sm rounded-circle overflow-hidden" style="width: 40px; height: 40px;">
                                <img src="${foto}" alt="${associado.name}" class="w-100 h-100 object-fit-cover">
                            </div>
                        </td>
                        <td>${associado.name}</td>
                        <td>${associado.matricula || 'N/A'}</td>
                        <td>${associado.cpf || 'N/A'}</td>
                        <td>${idade}</td>
                        <td>${associado.sexo ? associado.sexo.charAt(0).toUpperCase() + associado.sexo.slice(1) : 'N/A'}</td>
                        <td>${associado.bairro || 'N/A'}</td>
                        <td>${associado.logradouro || 'N/A'}</td>
                        <td>${tipoAssociado}</td>
                        <td>${statusBadge}</td>
                        <td>${new Date(associado.created_at).toLocaleDateString('pt-BR')}</td>
                        <td>
                            <a href="/admin/associados/detalhes/${associado.id}" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#corpoTabela').html(html);
        $('#totalResultados').text(associados.length).show();
    }

    function exibirEstatisticas(estatisticas) {
        let html = `
            <div class="col-md-3">
                <div class="text-center">
                    <h4 class="text-primary">${estatisticas.total}</h4>
                    <p class="text-muted mb-0">Total de Associados</p>
                </div>
            </div>
        `;

        // Por Sexo
        if (estatisticas.por_sexo && Object.keys(estatisticas.por_sexo).length > 0) {
            html += `
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Por Sexo</h6>
                        ${Object.entries(estatisticas.por_sexo).map(([sexo, count]) => 
                            `<small class="d-block">${sexo.charAt(0).toUpperCase() + sexo.slice(1)}: ${count}</small>`
                        ).join('')}
                    </div>
                </div>
            `;
        }

        // Por Tipo
        if (estatisticas.por_tipo && Object.keys(estatisticas.por_tipo).length > 0) {
            html += `
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Por Tipo</h6>
                        ${Object.entries(estatisticas.por_tipo).map(([tipo, count]) => 
                            `<small class="d-block">${tipo.charAt(0).toUpperCase() + tipo.slice(1)}: ${count}</small>`
                        ).join('')}
                    </div>
                </div>
            `;
        }

        // Por Status
        if (estatisticas.por_status && Object.keys(estatisticas.por_status).length > 0) {
            html += `
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Por Status</h6>
                        ${Object.entries(estatisticas.por_status).map(([status, count]) => 
                            `<small class="d-block">${status.charAt(0).toUpperCase() + status.slice(1)}: ${count}</small>`
                        ).join('')}
                    </div>
                </div>
            `;
        }

        $('#estatisticasContent').html(html);
        $('#estatisticas').show();
    }
});
</script>
@endpush
