@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Lista de Presenças - {{ $assembleia->titulo }}</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assembleias.index') }}">Assembleias</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assembleias.show', $assembleia->id) }}">{{ $assembleia->titulo }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Presenças</li>
                    </ol>
                </nav>
            </div>
        </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Presenças Registradas</h5>
                                    <p class="text-muted mb-0">
                                        {{ $assembleia->data_assembleia->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($assembleia->hora_inicio)->format('H:i') }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.assembleias.exportar-presencas', $assembleia->id) }}" class="btn btn-success">
                                        <i class="ri-download-line me-1"></i>Exportar CSV
                                    </a>
                                    <a href="{{ route('admin.assembleias.show', $assembleia->id) }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line me-1"></i>Voltar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="card border-0 bg-primary bg-opacity-10">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary mb-1">{{ $assembleia->presencas->count() }}</h3>
                                            <p class="text-muted mb-0">Total de Presenças</p>
                                        </div>
                                    </div>
                                </div>
                                @if($assembleia->quorum_minimo)
                                    <div class="col-md-3">
                                        <div class="card border-0 {{ $assembleia->atingiuQuorum() ? 'bg-success' : 'bg-warning' }} bg-opacity-10">
                                            <div class="card-body text-center">
                                                <h3 class="{{ $assembleia->atingiuQuorum() ? 'text-success' : 'text-warning' }} mb-1">
                                                    {{ $assembleia->presencas->count() }}/{{ $assembleia->quorum_minimo }}
                                                </h3>
                                                <p class="text-muted mb-0">Quorum</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="card border-0 bg-info bg-opacity-10">
                                        <div class="card-body text-center">
                                            <h3 class="text-info mb-1">
                                                {{ $assembleia->presencas->where('user_id', '!=', null)->count() }}
                                            </h3>
                                            <p class="text-muted mb-0">Associados Cadastrados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-0 bg-warning bg-opacity-10">
                                        <div class="card-body text-center">
                                            <h3 class="text-warning mb-1">
                                                {{ $assembleia->presencas->where('user_id', null)->count() }}
                                            </h3>
                                            <p class="text-muted mb-0">Visitantes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="presencasTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>CPF</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Data/Hora Presença</th>
                                            <th>Tipo</th>
                                            <th>Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assembleia->presencas as $index => $presenca)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $presenca->nome ?? ($presenca->user ? $presenca->user->name : 'N/A') }}</strong>
                                                    @if($presenca->user)
                                                        <br><small class="text-success">Associado Cadastrado</small>
                                                    @else
                                                        <br><small class="text-warning">Visitante</small>
                                                    @endif
                                                </td>
                                                <td>{{ $presenca->cpf ? substr($presenca->cpf, 0, 3) . '.***.**' . substr($presenca->cpf, -2) : 'N/A' }}</td>
                                                <td>{{ $presenca->email ?? ($presenca->user ? $presenca->user->email : 'N/A') }}</td>
                                                <td>{{ $presenca->telefone ?? ($presenca->user ? $presenca->user->telefone : 'N/A') }}</td>
                                                <td>
                                                    {{ $presenca->data_presenca->format('d/m/Y') }}<br>
                                                    <small class="text-muted">{{ $presenca->data_presenca->format('H:i:s') }}</small>
                                                </td>
                                                <td>
                                                    @if($presenca->user)
                                                        <span class="badge bg-success">Associado</span>
                                                    @else
                                                        <span class="badge bg-warning">Visitante</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($presenca->observacoes)
                                                        <small>{{ Str::limit($presenca->observacoes, 50) }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
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
    </div>
</main>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#presencasTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        order: [[5, 'desc']], // Ordenar por data/hora de presença
        columnDefs: [
            { targets: [0], orderable: false } // Desabilitar ordenação na coluna #
        ]
    });
});
</script>
@endpush
