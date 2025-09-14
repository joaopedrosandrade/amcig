@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Lista de Presenças - {{ $evento->titulo }}</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.eventos.index') }}">Eventos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.eventos.show', $evento->id) }}">{{ $evento->titulo }}</a></li>
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
                                    {{ $evento->data_evento->format('d/m/Y') }} às {{ $evento->hora_inicio }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.eventos.exportar-presencas', $evento->id) }}" class="btn btn-success">
                                    <i class="ri-download-line me-1"></i>Exportar CSV
                                </a>
                                <a href="{{ route('admin.eventos.show', $evento->id) }}" class="btn btn-secondary">
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
                                        <h3 class="text-primary mb-1">{{ $evento->presencas->count() }}</h3>
                                        <p class="text-muted mb-0">Total de Presenças</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-success bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="text-success mb-1">{{ $evento->presencas->where('user_id', '!=', null)->count() }}</h3>
                                        <p class="text-muted mb-0">Associados</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-warning bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-1">{{ $evento->presencas->where('user_id', null)->count() }}</h3>
                                        <p class="text-muted mb-0">Visitantes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-info bg-opacity-10">
                                    <div class="card-body text-center">
                                        @if($evento->quorum_minimo)
                                            <h3 class="text-info mb-1">{{ $evento->presencas->count() }}/{{ $evento->quorum_minimo }}</h3>
                                            <p class="text-muted mb-0">Quórum</p>
                                            @if($evento->atingiuQuorum())
                                                <small class="text-success"><i class="ri-check-line"></i> Atingido</small>
                                            @else
                                                <small class="text-warning"><i class="ri-alert-line"></i> Não atingido</small>
                                            @endif
                                        @else
                                            <h3 class="text-info mb-1">-</h3>
                                            <p class="text-muted mb-0">Sem Quórum</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($evento->presencas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="presencasTable">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>CPF</th>
                                            <th>E-mail</th>
                                            <th>Telefone</th>
                                            <th>Data/Hora Presença</th>
                                            <th>Tipo</th>
                                            <th>Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($evento->presencas as $presenca)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($presenca->user)
                                                            <div class="avatar-sm rounded-circle me-2 overflow-hidden" style="width: 32px; height: 32px;">
                                                                <img src="{{ $presenca->user->photo_url }}" alt="{{ $presenca->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                            </div>
                                                        @else
                                                            <div class="avatar-sm rounded-circle me-2 bg-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                <i class="ri-user-line text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $presenca->nome ?? $presenca->user->name ?? 'N/A' }}</strong>
                                                            @if($presenca->user)
                                                                <br><small class="text-muted">{{ $presenca->user->matricula ? 'Matrícula: ' . $presenca->user->matricula : 'Associado' }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $presenca->cpf ?? $presenca->user->cpf ?? 'N/A' }}</td>
                                                <td>{{ $presenca->email ?? $presenca->user->email ?? 'N/A' }}</td>
                                                <td>{{ $presenca->telefone ?? $presenca->user->telefone ?? 'N/A' }}</td>
                                                <td>{{ $presenca->data_presenca->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    @if($presenca->user)
                                                        <span class="badge bg-success">Associado</span>
                                                    @else
                                                        <span class="badge bg-warning">Visitante</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($presenca->observacoes)
                                                        <small>{{ \Illuminate\Support\Str::limit($presenca->observacoes, 50) }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ri-user-line text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">Nenhuma presença registrada</h5>
                                <p class="text-muted">Ainda não há presenças registradas para este evento.</p>
                                <a href="{{ route('admin.eventos.show', $evento->id) }}" class="btn btn-primary">
                                    <i class="ri-arrow-left-line me-1"></i>Voltar ao Evento
                                </a>
                            </div>
                        @endif
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
        order: [[4, 'desc']], // Ordenar por data/hora de presença
        columnDefs: [
            { targets: [1, 2, 3], orderable: false }, // CPF, Email, Telefone não ordenáveis
            { targets: [6], orderable: false } // Observações não ordenável
        ]
    });
});
</script>
@endpush
