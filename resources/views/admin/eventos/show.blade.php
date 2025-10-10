@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Detalhes do Evento</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.eventos.index') }}">Eventos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informações do Evento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Título:</h6>
                                <p class="mb-3">{{ $evento->titulo }}</p>
                                
                                <h6 class="fw-bold mb-2">Tipo:</h6>
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
                                <p class="mb-3"><span class="badge bg-{{ $tipo['badge'] }}">{{ $tipo['text'] }}</span></p>
                                
                                <h6 class="fw-bold mb-2">Data e Horário:</h6>
                                <p class="mb-3">{{ $evento->data_evento->format('d/m/Y') }} às {{ $evento->hora_inicio }}
                                    @if($evento->hora_fim)
                                        - {{ $evento->hora_fim }}
                                    @endif
                                </p>
                                
                                <h6 class="fw-bold mb-2">Local:</h6>
                                <p class="mb-3">{{ $evento->local }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Status:</h6>
                                @php
                                    $status = [
                                        'agendado' => ['badge' => 'info', 'text' => 'Agendado'],
                                        'em_andamento' => ['badge' => 'warning', 'text' => 'Em Andamento'],
                                        'concluido' => ['badge' => 'success', 'text' => 'Concluído'],
                                        'cancelado' => ['badge' => 'danger', 'text' => 'Cancelado']
                                    ];
                                    $statusInfo = $status[$evento->status] ?? ['badge' => 'secondary', 'text' => ucfirst($evento->status)];
                                @endphp
                                <p class="mb-3"><span class="badge bg-{{ $statusInfo['badge'] }}">{{ $statusInfo['text'] }}</span></p>
                                
                                <h6 class="fw-bold mb-2">Presenças Registradas:</h6>
                                <p class="mb-3">
                                    <span class="badge bg-info">{{ $evento->total_presencas }} participantes</span>
                                    @if($evento->quorum_minimo)
                                        <small class="text-muted d-block">Quórum mínimo: {{ $evento->quorum_minimo }}</small>
                                        @if($evento->atingiuQuorum())
                                            <span class="badge bg-success mt-1">Quórum atingido</span>
                                        @else
                                            <span class="badge bg-warning mt-1">Quórum não atingido</span>
                                        @endif
                                    @endif
                                </p>
                                
                                <h6 class="fw-bold mb-2">Lista de Presença:</h6>
                                <p class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-lista" type="checkbox" role="switch"
                                               data-id="{{ $evento->id }}"
                                               {{ $evento->lista_presenca_ativa ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $evento->lista_presenca_ativa ? 'Ativa' : 'Inativa' }}
                                        </label>
                                    </div>
                                </p>
                                
                                @if($evento->link_presenca)
                                    <h6 class="fw-bold mb-2">Link de Presença:</h6>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ route('evento.presenca', $evento->link_presenca) }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="copiarLink('{{ route('evento.presenca', $evento->link_presenca) }}')">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($evento->descricao)
                            <h6 class="fw-bold mb-2">Descrição:</h6>
                            <p class="mb-3">{{ $evento->descricao }}</p>
                        @endif
                        
                        @if($evento->pauta)
                            <h6 class="fw-bold mb-2">Pauta:</h6>
                            <div class="mb-3">
                                <pre class="bg-light p-3 rounded">{{ $evento->pauta }}</pre>
                            </div>
                        @endif
                        
                        @if($evento->observacoes)
                            <h6 class="fw-bold mb-2">Observações:</h6>
                            <p class="mb-3">{{ $evento->observacoes }}</p>
                        @endif
                    </div>
                </div>

                <!-- Informações Financeiras do Evento -->
                @if($evento->contasPagar->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-money-dollar-circle-line me-2"></i>Gestão Financeira do Evento</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cards de Resumo Financeiro -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <p class="text-muted mb-1 fs-12">Total de Despesas</p>
                                    <h5 class="mb-0">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <p class="text-muted mb-1 fs-12">Despesas Pagas</p>
                                    <h5 class="mb-0 text-success">R$ {{ number_format($totalDespesasPagas, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <p class="text-muted mb-1 fs-12">Despesas Pendentes</p>
                                    <h5 class="mb-0 text-warning">R$ {{ number_format($totalDespesasPendentes, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela de Contas a Pagar -->
                        <h6 class="text-primary mb-3"><i class="ri-arrow-down-circle-line me-2"></i>Contas a Pagar do Evento</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Fornecedor</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evento->contasPagar as $conta)
                                    <tr>
                                        <td>
                                            <strong>{{ $conta->descricao }}</strong>
                                            <br><small class="text-muted">{{ $conta->categoria }}</small>
                                        </td>
                                        <td>{{ $conta->fornecedor }}</td>
                                        <td>
                                            {{ $conta->data_vencimento_formatada }}
                                            @if($conta->isVencida())
                                                <br><small class="text-danger">{{ $conta->dias_atraso }}d atraso</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $conta->valor_formatado }}</strong>
                                            @if($conta->isPaga() && $conta->contaBancaria)
                                                <br><small class="text-muted">
                                                    <i class="ri-bank-line"></i> {{ $conta->contaBancaria->nome }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $conta->status_badge_class }}">
                                                {{ $conta->status_texto }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.fluxo-caixa.contas-pagar.show', $conta->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.fluxo-caixa.contas-pagar', ['evento_id' => $evento->id]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="ri-external-link-line me-1"></i> Ver Todas as Contas deste Evento
                            </a>
                            <a href="{{ route('admin.fluxo-caixa.contas-pagar.create') }}?evento_id={{ $evento->id }}" 
                               class="btn btn-outline-success btn-sm">
                                <i class="ri-add-line me-1"></i> Adicionar Despesa ao Evento
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ações</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.eventos.edit', $evento->id) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-1"></i>Editar Evento
                            </a>
                            
                            @if(!$evento->link_presenca)
                                <button class="btn btn-success" onclick="gerarLink({{ $evento->id }})">
                                    <i class="ri-link me-1"></i>Gerar Link Presença
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.eventos.presencas', $evento->id) }}" class="btn btn-info">
                                <i class="ri-user-line me-1"></i>Ver Presenças ({{ $evento->total_presencas }})
                            </a>
                            
                            <a href="{{ route('admin.eventos.exportar-presencas', $evento->id) }}" class="btn btn-warning">
                                <i class="ri-download-line me-1"></i>Exportar Presenças
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Link de Presença</h5>
                    </div>
                    <div class="card-body">
                        @if($evento->link_presenca)
                            <div class="alert alert-success">
                                <i class="ri-check-line me-2"></i>
                                Link gerado com sucesso!
                            </div>
                            <p><strong>URL:</strong> <a href="{{ route('evento.presenca', $evento->link_presenca) }}" target="_blank">Acessar Lista de Presença</a></p>
                        @else
                            <div class="alert alert-warning">
                                <i class="ri-alert-line me-2"></i>
                                Nenhum link de presença gerado.
                            </div>
                            <button class="btn btn-primary btn-sm" onclick="gerarLink({{ $evento->id }})">
                                <i class="ri-link me-1"></i>Gerar Link
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
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
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = link;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        toastr.success('Link copiado para a área de transferência!');
    });
}

function toggleLista(id) {
    $.ajax({
        url: `{{ url('admin/eventos') }}/${id}/toggle-lista`,
        method: 'POST',
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
            toastr.error('Erro ao alterar status da lista de presença');
            location.reload();
        }
    });
}
</script>
@endpush
