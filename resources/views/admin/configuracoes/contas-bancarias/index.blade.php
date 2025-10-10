@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Contas Bancárias</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Configurações</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contas Bancárias</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Mensagens -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Listagem de Contas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Contas Bancárias Cadastradas</h5>
                        <a href="{{ route('admin.contas-bancarias.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i> Nova Conta Bancária
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome da Conta</th>
                                        <th>Banco</th>
                                        <th>Agência / Conta</th>
                                        <th>Tipo</th>
                                        <th>Saldo Atual</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contas as $conta)
                                        <tr>
                                            <td>
                                                <strong>{{ $conta->nome }}</strong>
                                                @if($conta->principal)
                                                    <span class="badge bg-primary-subtle text-primary ms-2">Principal</span>
                                                @endif
                                            </td>
                                            <td>{{ $conta->banco }}</td>
                                            <td>
                                                @if($conta->agencia || $conta->numero_conta)
                                                    Ag: {{ $conta->agencia ?? '-' }} / CC: {{ $conta->numero_conta ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $conta->tipo_conta_texto }}</td>
                                            <td>
                                                <strong class="{{ $conta->saldo_atual >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $conta->saldo_formatado }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge {{ $conta->ativo ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $conta->ativo ? 'Ativa' : 'Inativa' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.contas-bancarias.edit', $conta->id) }}" 
                                                       class="btn btn-outline-primary" title="Editar">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            title="Excluir" onclick="confirmarExclusao({{ $conta->id }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                                
                                                <form id="form-delete-{{ $conta->id }}" 
                                                      action="{{ route('admin.contas-bancarias.destroy', $conta->id) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="ri-bank-line" style="font-size: 3rem;"></i>
                                                <p class="mb-0 mt-2">Nenhuma conta bancária cadastrada</p>
                                                <small class="text-muted">Clique em "Nova Conta Bancária" para adicionar</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($contas->hasPages())
                            <div class="mt-3">
                                {{ $contas->links() }}
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

