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

        <!-- Estatísticas Principais -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Total a Pagar</p>
                                <h4 class="mb-0">R$ 0,00</h4>
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
            
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Pagas este Mês</p>
                                <h4 class="mb-0">R$ 0,00</h4>
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
            
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Vencendo Hoje</p>
                                <h4 class="mb-0">R$ 0,00</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                                    <i class="ri-time-line text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Em Atraso</p>
                                <h4 class="mb-0 text-danger">R$ 0,00</h4>
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

        <!-- Tabela de Contas a Pagar -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Lista de Contas a Pagar</h5>
                        <a href="{{ route('admin.fluxo-caixa.contas-pagar.create') }}" class="btn btn-primary btn-sm">
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
                                            </td>
                                            <td>{{ $conta->categoria }}</td>
                                            <td>{{ $conta->fornecedor }}</td>
                                            <td>
                                                {{ $conta->data_vencimento_formatada }}
                                                @if($conta->isVencida())
                                                    <br><small class="text-danger">{{ $conta->dias_atraso }} dias de atraso</small>
                                                @endif
                                            </td>
                                            <td>{{ $conta->valor_formatado }}</td>
                                            <td>
                                                <span class="badge {{ $conta->status_badge_class }}">
                                                    {{ $conta->status_texto }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.fluxo-caixa.contas-pagar.edit', $conta->id) }}" 
                                                       class="btn btn-outline-primary" title="Editar">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    @if($conta->isPendente())
                                                        <button type="button" class="btn btn-outline-success" 
                                                                title="Registrar Pagamento" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalPagar{{ $conta->id }}">
                                                            <i class="ri-money-dollar-circle-line"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            title="Excluir"
                                                            onclick="confirmarExclusao({{ $conta->id }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                                
                                                <form id="form-delete-{{ $conta->id }}" 
                                                      action="{{ route('admin.fluxo-caixa.contas-pagar.destroy', $conta->id) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="ri-file-list-line" style="font-size: 3rem;"></i>
                                                <p class="mb-0 mt-2">Nenhuma conta a pagar cadastrada</p>
                                                <small class="text-muted">Clique em "Nova Conta" para adicionar</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($contas->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Mostrando {{ $contas->firstItem() }} a {{ $contas->lastItem() }} de {{ $contas->total() }} registros
                                    </div>
                                    <div>
                                        {{ $contas->links() }}
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

