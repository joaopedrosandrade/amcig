@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Parcerias</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Parcerias</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-handshake-line me-2"></i>Gerenciar Parcerias
                        </h5>
                        <a href="{{ route('admin.parcerias.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Nova Parceria
                        </a>
                    </div>
                    <div class="card-body">
                        @if($parcerias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Empresa</th>
                                            <th>Categoria</th>
                                            <th>Desconto</th>
                                            <th>Status</th>
                                            <th>Destaque</th>
                                            <th>Ordem</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($parcerias as $parceria)
                                            <tr>
                                                <td>
                                                    @if($parceria->logo)
                                                        <img src="{{ $parceria->logo_url }}" alt="{{ $parceria->nome_empresa }}" 
                                                             class="rounded" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="ri-building-line text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($parceria->logo)
                                                            <img src="{{ asset('storage/logos/' . $parceria->logo) }}" 
                                                                 alt="Logo {{ $parceria->nome_empresa }}" 
                                                                 style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;" 
                                                                 class="me-3 border">
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="ri-building-line text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $parceria->nome_empresa }}</strong>
                                                            @if($parceria->descricao)
                                                                <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($parceria->descricao, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ ucfirst($parceria->categoria) }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong class="text-success">{{ $parceria->desconto_formatado }}</strong>
                                                        @if($parceria->valor_minimo_pedido)
                                                            <br><small class="text-muted">Mín: R$ {{ number_format($parceria->valor_minimo_pedido, 2, ',', '.') }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" 
                                                               type="checkbox" 
                                                               id="status_{{ $parceria->id }}"
                                                               {{ $parceria->ativo ? 'checked' : '' }}
                                                               data-id="{{ $parceria->id }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-destaque" 
                                                               type="checkbox" 
                                                               id="destaque_{{ $parceria->id }}"
                                                               {{ $parceria->destaque ? 'checked' : '' }}
                                                               data-id="{{ $parceria->id }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $parceria->ordem }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.parcerias.show', $parceria->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="{{ route('admin.parcerias.edit', $parceria->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Editar">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger excluir-parceria" 
                                                                data-id="{{ $parceria->id }}" title="Excluir">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-handshake-line text-muted" style="font-size: 4rem;"></i>
                                <h4 class="text-muted mt-3">Nenhuma parceria encontrada</h4>
                                <p class="text-muted">Comece criando sua primeira parceria.</p>
                                <a href="{{ route('admin.parcerias.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-1"></i>Nova Parceria
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

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').on('change', function() {
        const parceriaId = $(this).data('id');
        const isActive = $(this).is(':checked');
        
        $.ajax({
            url: `{{ url('admin/parcerias') }}/${parceriaId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    // Reverte o toggle se houve erro
                    $(`#status_${parceriaId}`).prop('checked', !isActive);
                }
            },
            error: function() {
                toastr.error('Erro ao atualizar status');
                // Reverte o toggle se houve erro
                $(`#status_${parceriaId}`).prop('checked', !isActive);
            }
        });
    });

    // Toggle destaque
    $('.toggle-destaque').on('change', function() {
        const parceriaId = $(this).data('id');
        const isDestaque = $(this).is(':checked');
        
        $.ajax({
            url: `{{ url('admin/parcerias') }}/${parceriaId}/toggle-destaque`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    // Reverte o toggle se houve erro
                    $(`#destaque_${parceriaId}`).prop('checked', !isDestaque);
                }
            },
            error: function() {
                toastr.error('Erro ao atualizar destaque');
                // Reverte o toggle se houve erro
                $(`#destaque_${parceriaId}`).prop('checked', !isDestaque);
            }
        });
    });

    // Excluir parceria
    $('.excluir-parceria').on('click', function() {
        const parceriaId = $(this).data('id');
        
        if (confirm('Tem certeza que deseja excluir esta parceria?')) {
            $.ajax({
                url: `{{ url('admin/parcerias') }}/${parceriaId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Erro ao excluir parceria');
                }
            });
        }
    });
});
</script>
@endpush
