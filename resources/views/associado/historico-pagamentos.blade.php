@extends('layouts.associado')

@section('title', 'Histórico de Pagamentos - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Histórico de Pagamentos</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item active">Histórico de Pagamentos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total de Pagamentos</h6>
                            <h4 class="mb-0">{{ $totalPagamentos }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-wallet-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Valor Total Pago</h6>
                            <h4 class="mb-0">R$ {{ number_format($valorTotal, 2, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('associado.historico-pagamentos') }}" id="filtros-form">
                            <div class="row g-3">
                                <!-- Período -->
                                <div class="col-md-2">
                                    <label for="data_inicio" class="form-label">Data Início</label>
                                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                                           value="{{ $filtros['data_inicio'] ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="data_fim" class="form-label">Data Fim</label>
                                    <input type="date" class="form-control" id="data_fim" name="data_fim" 
                                           value="{{ $filtros['data_fim'] ?? '' }}">
                                </div>
                                
                                <!-- Status -->
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos os Status</option>
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" {{ ($filtros['status'] ?? '') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Método de Pagamento -->
                                <div class="col-md-2">
                                    <label for="metodo_pagamento" class="form-label">Método</label>
                                    <select class="form-select" id="metodo_pagamento" name="metodo_pagamento">
                                        <option value="">Todos os Métodos</option>
                                        @foreach($metodoOptions as $value => $label)
                                            <option value="{{ $value }}" {{ ($filtros['metodo_pagamento'] ?? '') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Valores -->
                                <div class="col-md-2">
                                    <label for="valor_minimo" class="form-label">Valor Mínimo</label>
                                    <input type="number" class="form-control" id="valor_minimo" name="valor_minimo" 
                                           step="0.01" min="0" value="{{ $filtros['valor_minimo'] ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="valor_maximo" class="form-label">Valor Máximo</label>
                                    <input type="number" class="form-control" id="valor_maximo" name="valor_maximo" 
                                           step="0.01" min="0" value="{{ $filtros['valor_maximo'] ?? '' }}">
                                </div>
                                
                                <!-- Busca -->
                                <div class="col-md-6">
                                    <label for="busca" class="form-label">Buscar por Descrição</label>
                                    <input type="text" class="form-control" id="busca" name="busca" 
                                           placeholder="Digite parte da descrição..." value="{{ $filtros['busca'] ?? '' }}">
                                </div>
                                
                                <!-- Botões -->
                                <div class="col-md-6 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-search-line me-1"></i>Filtrar
                                    </button>
                                    <a href="{{ route('associado.historico-pagamentos') }}" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line me-1"></i>Limpar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Pagamentos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="ri-history-line me-2"></i>Pagamentos Realizados
                            </h5>
                            <span class="text-muted">Total: {{ $pagamentos->total() }} pagamentos</span>
                        </div>
                    @if($pagamentos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['ordenacao' => 'payment_date', 'direcao' => $ordenacao == 'payment_date' && $direcao == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none">
                                                Data do Pagamento
                                                @if($ordenacao == 'payment_date')
                                                    <i class="ri-arrow-{{ $direcao == 'asc' ? 'up' : 'down' }}-line"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Descrição</th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['ordenacao' => 'value', 'direcao' => $ordenacao == 'value' && $direcao == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none">
                                                Valor
                                                @if($ordenacao == 'value')
                                                    <i class="ri-arrow-{{ $direcao == 'asc' ? 'up' : 'down' }}-line"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['ordenacao' => 'status', 'direcao' => $ordenacao == 'status' && $direcao == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none">
                                                Status
                                                @if($ordenacao == 'status')
                                                    <i class="ri-arrow-{{ $direcao == 'asc' ? 'up' : 'down' }}-line"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['ordenacao' => 'payment_method', 'direcao' => $ordenacao == 'payment_method' && $direcao == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none">
                                                Método
                                                @if($ordenacao == 'payment_method')
                                                    <i class="ri-arrow-{{ $direcao == 'asc' ? 'up' : 'down' }}-line"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Fatura</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagamentos as $pagamento)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <div class="avatar-xs rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                                            <i class="ri-calendar-line text-success" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $pagamento->payment_date->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ $pagamento->payment_date->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $pagamento->description }}</div>
                                                @if($pagamento->asaas_payment_id)
                                                    <small class="text-muted">ID: {{ $pagamento->asaas_payment_id }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    R$ {{ number_format($pagamento->value, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @switch($pagamento->status)
                                                    @case('CONFIRMED')
                                                        <span class="badge bg-success">Confirmado</span>
                                                        @break
                                                    @case('RECEIVED')
                                                        <span class="badge bg-primary">Recebido</span>
                                                        @break
                                                    @case('RECEIVED_IN_CASH')
                                                        <span class="badge bg-info">Recebido em Dinheiro</span>
                                                        @break
                                                    @case('RECEIVED_WITH_OVERDUE')
                                                        <span class="badge bg-warning">Recebido com Atraso</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $pagamento->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($pagamento->payment_method)
                                                    @case('PIX')
                                                        <span class="badge bg-primary">PIX</span>
                                                        @break
                                                    @case('BOLETO')
                                                        <span class="badge bg-info">Boleto</span>
                                                        @break
                                                    @case('CREDIT_CARD')
                                                        <span class="badge bg-success">Cartão de Crédito</span>
                                                        @break
                                                    @case('DEBIT_CARD')
                                                        <span class="badge bg-warning">Cartão de Débito</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $pagamento->payment_method }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($pagamento->invoice)
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <div class="avatar-xs rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                                <i class="ri-file-text-line text-muted" style="font-size: 0.8rem;"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">#{{ $pagamento->invoice->id }}</div>
                                                            <small class="text-muted">{{ $pagamento->invoice->due_date->format('d/m/Y') }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if($pagamento->invoice)
                                                        <a href="{{ route('associado.ver-fatura', $pagamento->invoice->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Ver Fatura">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    @endif
                                                    @if($pagamento->asaas_payment_id)
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="copiarId('{{ $pagamento->asaas_payment_id }}')" title="Copiar ID">
                                                            <i class="ri-file-copy-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginação -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Mostrando {{ $pagamentos->firstItem() }} a {{ $pagamentos->lastItem() }} 
                                de {{ $pagamentos->total() }} resultados
                            </div>
                            <div>
                                {{ $pagamentos->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-light text-primary rounded-circle">
                                    <i class="ri-search-line" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Nenhum pagamento encontrado</h5>
                            <p class="text-muted mb-4">
                                @if(count($filtros) > 0)
                                    Não foram encontrados pagamentos com os filtros aplicados.
                                @else
                                    Você ainda não possui pagamentos registrados.
                                @endif
                            </p>
                            @if(count($filtros) > 0)
                                <a href="{{ route('associado.historico-pagamentos') }}" class="btn btn-primary">
                                    <i class="ri-refresh-line me-1"></i>Limpar Filtros
                                </a>
                            @endif
                        </div>
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
    // Função para copiar ID do pagamento
    function copiarId(id) {
        navigator.clipboard.writeText(id).then(function() {
            // Mostrar toast de sucesso
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ri-check-line me-2"></i>ID copiado para a área de transferência!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            // Adicionar ao container de toasts
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.appendChild(toast);
            
            // Mostrar toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remover após 3 segundos
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }).catch(function(err) {
            console.error('Erro ao copiar: ', err);
        });
    }
    
    // Auto-submit do formulário quando campos de data mudarem
    document.getElementById('data_inicio').addEventListener('change', function() {
        if (this.value && document.getElementById('data_fim').value) {
            document.getElementById('filtros-form').submit();
        }
    });
    
    document.getElementById('data_fim').addEventListener('change', function() {
        if (this.value && document.getElementById('data_inicio').value) {
            document.getElementById('filtros-form').submit();
        }
    });
</script>
@endpush
