@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Pagamentos</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.financeiro.index') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pagamentos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.financeiro.pagamentos') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Todos os Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                            <select name="metodo_pagamento" id="metodo_pagamento" class="form-select">
                                <option value="">Todos os Métodos</option>
                                @foreach($metodosPagamento as $metodo)
                                    <option value="{{ $metodo }}" {{ request('metodo_pagamento') == $metodo ? 'selected' : '' }}>
                                        {{ $metodo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Filtrar
                            </button>
                            <a href="{{ route('admin.financeiro.pagamentos') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Limpar Filtros
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Pagamentos -->
        <div class="card">
            <div class="card-header pb-0">
                <h4>Lista de Pagamentos</h4>
                <p class="text-muted mb-0">Total de {{ $pagamentos->total() }} pagamentos encontrados</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Associado</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Método</th>
                                <th>Data Pagamento</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagamentos as $pagamento)
                            <tr>
                                <td>{{ $pagamento->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $pagamento->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $pagamento->user->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $pagamento->formatted_value }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'info';
                                        switch($pagamento->status) {
                                            case 'CONFIRMED':
                                            case 'RECEIVED_IN_CASH':
                                            case 'RECEIVED_WITH_OVERDUE':
                                                $statusClass = 'success';
                                                break;
                                            case 'PENDING':
                                                $statusClass = 'warning';
                                                break;
                                            case 'OVERDUE':
                                                $statusClass = 'danger';
                                                break;
                                            case 'REFUNDED':
                                                $statusClass = 'secondary';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $pagamento->formatted_status }}</span>
                                </td>
                                <td>{{ $pagamento->payment_method ?? 'N/A' }}</td>
                                <td>{{ $pagamento->formatted_payment_date }}</td>
                                <td>{{ $pagamento->description ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalPagamento{{ $pagamento->id }}">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de Detalhes -->
                            <div class="modal fade" id="modalPagamento{{ $pagamento->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalhes do Pagamento #{{ $pagamento->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Informações do Pagamento</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td><strong>ID:</strong></td>
                                                            <td>{{ $pagamento->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Valor:</strong></td>
                                                            <td>{{ $pagamento->formatted_value }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Status:</strong></td>
                                                            <td><span class="badge bg-{{ $statusClass }}">{{ $pagamento->formatted_status }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Método:</strong></td>
                                                            <td>{{ $pagamento->payment_method ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Data:</strong></td>
                                                            <td>{{ $pagamento->formatted_payment_date }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Descrição:</strong></td>
                                                            <td>{{ $pagamento->description ?? 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Informações do Associado</h6>
                                                    @if($pagamento->user)
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td><strong>Nome:</strong></td>
                                                            <td>{{ $pagamento->user->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Email:</strong></td>
                                                            <td>{{ $pagamento->user->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Matrícula:</strong></td>
                                                            <td>{{ $pagamento->user->matricula ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Tipo:</strong></td>
                                                            <td>{{ ucfirst($pagamento->user->tipo_associado ?? 'N/A') }}</td>
                                                        </tr>
                                                    </table>
                                                    @else
                                                    <p class="text-muted">Associado não encontrado</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($pagamento->asaas_data)
                                            <div class="mt-3">
                                                <h6>Dados do Asaas</h6>
                                                <pre class="bg-light p-3 rounded"><code>{{ json_encode($pagamento->asaas_data, JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line fs-48 mb-3 d-block"></i>
                                        Nenhum pagamento encontrado com os filtros aplicados.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($pagamentos->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $pagamentos->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
