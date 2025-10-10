@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Contas a Pagar</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Financeiro</a></li>
                        <li class="breadcrumb-item"><a href="#">Fluxo de Caixa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contas a Pagar</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Cards de Resumo -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Total a Pagar</p>
                                <h4 class="mb-0">R$ {{ number_format($totalPagar, 2, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-arrow-down-line text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Pago este Mês</p>
                                <h4 class="mb-0">R$ {{ number_format($totalPago, 2, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-check-line text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Em Atraso</p>
                                <h4 class="mb-0 text-danger">R$ {{ number_format($totalVencido, 2, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-alert-line text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensagens de Sucesso/Erro -->
        @if(session('success'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filtros Rápidos -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.fluxo-caixa.contas-pagar') }}" 
                       class="btn btn-sm {{ !request()->hasAny(['status', 'evento_id', 'categoria_id', 'data_inicio', 'data_fim']) ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="ri-list-check me-1"></i> Todas
                    </a>
                    <a href="{{ route('admin.fluxo-caixa.contas-pagar', ['status' => 'pendente']) }}" 
                       class="btn btn-sm {{ request('status') == 'pendente' ? 'btn-warning' : 'btn-outline-warning' }}">
                        <i class="ri-time-line me-1"></i> Pendentes
                    </a>
                    <a href="{{ route('admin.fluxo-caixa.contas-pagar', ['status' => 'pago']) }}" 
                       class="btn btn-sm {{ request('status') == 'pago' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="ri-check-line me-1"></i> Pagas
                    </a>
                    <a href="{{ route('admin.fluxo-caixa.contas-pagar', ['data_inicio' => now()->startOfMonth()->format('Y-m-d'), 'data_fim' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                       class="btn btn-sm btn-outline-info">
                        <i class="ri-calendar-line me-1"></i> Mês Atual
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filtrosAvancados">
                        <i class="ri-filter-3-line me-1"></i> Filtros Avançados
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros Avançados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="collapse {{ request()->hasAny(['evento_id', 'categoria_id', 'data_inicio', 'data_fim']) ? 'show' : '' }}" id="filtrosAvancados">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.fluxo-caixa.contas-pagar') }}" id="formFiltros">
                                <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" value="{{ request('data_inicio') }}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" value="{{ request('data_fim') }}">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">Todos</option>
                                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                        <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Categoria</label>
                                    <select class="form-select" name="categoria_id">
                                        <option value="">Todas</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Evento</label>
                                    <select class="form-select" name="evento_id">
                                        <option value="">Todos</option>
                                        @foreach($eventos as $evento)
                                            <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                                {{ $evento->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ri-filter-line me-1"></i> Aplicar Filtros
                                        </button>
                                        <a href="{{ route('admin.fluxo-caixa.contas-pagar') }}" class="btn btn-secondary btn-sm">
                                            <i class="ri-refresh-line me-1"></i> Limpar Filtros
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Contas a Pagar -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Lista de Contas a Pagar
                            <small class="text-muted">({{ $contas->total() }} registro{{ $contas->total() != 1 ? 's' : '' }})</small>
                        </h5>
                        <a href="{{ route('admin.fluxo-caixa.contas-pagar.create') }}" class="btn btn-success btn-sm">
                            <i class="ri-add-line me-1"></i> Nova Conta
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Categoria</th>
                                        <th>Fornecedor</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contas as $conta)
                                        <tr>
                                            <td>
                                                <strong>{{ $conta->descricao }}</strong>
                                                @if($conta->numero_nota_fiscal)
                                                    <br><small class="text-muted">NF: {{ $conta->numero_nota_fiscal }}</small>
                                                @endif
                                                @if($conta->evento)
                                                    <br><small>
                                                        <a href="{{ route('admin.eventos.show', $conta->evento->id) }}" class="text-info text-decoration-none" title="Ver evento">
                                                            <i class="ri-calendar-event-line"></i> {{ $conta->evento->titulo }}
                                                        </a>
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $conta->categoria }}</td>
                                            <td>
                                                {{ $conta->fornecedor }}
                                                @if($conta->cnpj_fornecedor)
                                                    <br><small class="text-muted">{{ $conta->cnpj_fornecedor }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $conta->data_vencimento_formatada }}
                                                @if($conta->isVencida())
                                                    <br><small class="text-danger">{{ $conta->dias_atraso }} dias de atraso</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $conta->valor_formatado }}</strong>
                                                @if($conta->isPaga() && $conta->valor_pago)
                                                    <br><small class="text-success">Pago: R$ {{ number_format($conta->valor_pago, 2, ',', '.') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $conta->status_badge_class }}">
                                                    {{ $conta->status_texto }}
                                                </span>
                                                @if($conta->isPaga() && $conta->data_pagamento)
                                                    <br><small class="text-muted">{{ $conta->data_pagamento_formatada }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if($conta->isPaga())
                                                        <a href="{{ route('admin.fluxo-caixa.contas-pagar.show', $conta->id) }}" 
                                                           class="btn btn-outline-info" title="Visualizar">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.fluxo-caixa.contas-pagar.edit', $conta->id) }}" 
                                                           class="btn btn-outline-primary" title="Editar">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($conta->isPendente())
                                                        <button type="button" class="btn btn-outline-success" 
                                                                title="Registrar Pagamento" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalPagar{{ $conta->id }}">
                                                            <i class="ri-money-dollar-circle-line"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if(!$conta->isPaga())
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                title="Excluir"
                                                                onclick="confirmarExclusao({{ $conta->id }})">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                
                                                <form id="form-delete-{{ $conta->id }}" 
                                                      action="{{ route('admin.fluxo-caixa.contas-pagar.destroy', $conta->id) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal de Pagamento -->
                                        @if($conta->isPendente())
                                        <div class="modal fade" id="modalPagar{{ $conta->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Registrar Pagamento</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.fluxo-caixa.contas-pagar.pagar', $conta->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Conta:</strong> {{ $conta->descricao }}<br>
                                                                <strong>Valor Original:</strong> {{ $conta->valor_formatado }}<br>
                                                                <strong>Fornecedor:</strong> {{ $conta->fornecedor }}
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Data do Pagamento <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" name="data_pagamento" value="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Conta Bancária <span class="text-danger">*</span></label>
                                                                <select class="form-select" name="conta_bancaria_id" required>
                                                                    <option value="">Selecione a conta...</option>
                                                                    @foreach($contasBancarias as $cb)
                                                                        <option value="{{ $cb->id }}" {{ $cb->principal ? 'selected' : '' }}>
                                                                            {{ $cb->nome_completo }} - {{ $cb->saldo_formatado }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-muted">O saldo desta conta será debitado</small>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Forma de Pagamento <span class="text-danger">*</span></label>
                                                                <select class="form-select" name="forma_pagamento" required>
                                                                    <option value="">Selecione...</option>
                                                                    <option value="dinheiro">Dinheiro</option>
                                                                    <option value="pix">PIX</option>
                                                                    <option value="transferencia">Transferência</option>
                                                                    <option value="boleto">Boleto</option>
                                                                    <option value="cartao_credito">Cartão de Crédito</option>
                                                                    <option value="cartao_debito">Cartão de Débito</option>
                                                                    <option value="cheque">Cheque</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Valor Pago <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="valor_pago" 
                                                                           value="{{ $conta->valor }}" step="0.01" min="0" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Desconto</label>
                                                                    <input type="number" class="form-control" name="desconto" 
                                                                           value="0" step="0.01" min="0">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Juros</label>
                                                                    <input type="number" class="form-control" name="juros" 
                                                                           value="0" step="0.01" min="0">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Multa</label>
                                                                    <input type="number" class="form-control" name="multa" 
                                                                           value="0" step="0.01" min="0">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Comprovante de Pagamento</label>
                                                                <input type="file" class="form-control" name="comprovante_pagamento" 
                                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                                <small class="text-muted">Máx: 5MB</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="ri-check-line me-1"></i> Confirmar Pagamento
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="ri-file-list-line" style="font-size: 3rem;"></i>
                                                <p class="mb-0 mt-2">Nenhuma conta a pagar encontrada</p>
                                                <small class="text-muted">
                                                    @if(request()->hasAny(['status', 'evento_id', 'categoria_id', 'data_inicio', 'data_fim']))
                                                        Tente alterar os filtros ou <a href="{{ route('admin.fluxo-caixa.contas-pagar') }}?todos=1">limpar os filtros</a>
                                                    @else
                                                        Clique em "Nova Conta" para adicionar
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($contas->hasPages())
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Mostrando {{ $contas->firstItem() }} a {{ $contas->lastItem() }} de {{ $contas->total() }} registros
                                    </div>
                                    <div>
                                        {{ $contas->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarExclusao(id) {
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
            document.getElementById('form-delete-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection
