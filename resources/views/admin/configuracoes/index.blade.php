@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Configurações do Sistema</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Configurações</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-settings-3-line me-2"></i>Configurações do Sistema
                        </h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="inicializarConfigs">
                            <i class="ri-database-2-line me-1"></i>Inicializar Configurações Padrão
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Controle de Visibilidade:</strong> Use estas configurações para controlar quais funcionalidades estão visíveis para os associados. 
                            Você pode liberar funcionalidades aos poucos conforme necessário.
                        </div>

                        @if($configuracoesPorCategoria->isEmpty())
                            <div class="text-center py-5">
                                <i class="ri-settings-3-line text-muted" style="font-size: 4rem;"></i>
                                <h4 class="text-muted mt-3">Nenhuma configuração encontrada</h4>
                                <p class="text-muted">Clique em "Inicializar Configurações Padrão" para começar.</p>
                            </div>
                        @else
                            @foreach($configuracoesPorCategoria as $categoria => $configuracoes)
                                <div class="mb-5">
                                    <h6 class="text-primary mb-3">
                                        <i class="ri-folder-line me-2"></i>
                                        {{ ucfirst($categoria) }}
                                    </h6>
                                    
                                    <div class="row">
                                        @foreach($configuracoes as $config)
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="card-title mb-1">{{ $config->nome }}</h6>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input toggle-config" 
                                                                       type="checkbox" 
                                                                       id="config_{{ $config->id }}"
                                                                       {{ $config->ativo ? 'checked' : '' }}
                                                                       data-id="{{ $config->id }}">
                                                            </div>
                                                        </div>
                                                        
                                                        @if($config->descricao)
                                                            <p class="card-text text-muted small mb-3">{{ $config->descricao }}</p>
                                                        @endif
                                                        
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge badge-sm {{ $config->ativo ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ $config->ativo ? 'Ativo' : 'Inativo' }}
                                                            </span>
                                                            
                                                            @if(!is_null($config->valor_boolean))
                                                                <span class="badge badge-sm bg-info ms-2">
                                                                    {{ $config->valor_boolean ? 'Sim' : 'Não' }}
                                                                </span>
                                                            @elseif(!is_null($config->valor_string))
                                                                <span class="badge badge-sm bg-info ms-2">
                                                                    {{ $config->valor_string }}
                                                                </span>
                                                            @elseif(!is_null($config->valor_integer))
                                                                <span class="badge badge-sm bg-info ms-2">
                                                                    {{ $config->valor_integer }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
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
    // Toggle de ativação/desativação
    $('.toggle-config').on('change', function() {
        const configId = $(this).data('id');
        const isActive = $(this).is(':checked');
        
        $.ajax({
            url: `{{ url('admin/configuracoes') }}/${configId}/toggle`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Atualiza o badge de status
                    const badge = $(`#config_${configId}`).closest('.card-body').find('.badge');
                    if (response.ativo) {
                        badge.removeClass('bg-secondary').addClass('bg-success').text('Ativo');
                    } else {
                        badge.removeClass('bg-success').addClass('bg-secondary').text('Inativo');
                    }
                } else {
                    toastr.error(response.message);
                    // Reverte o toggle se houve erro
                    $(`#config_${configId}`).prop('checked', !isActive);
                }
            },
            error: function() {
                toastr.error('Erro ao atualizar configuração');
                // Reverte o toggle se houve erro
                $(`#config_${configId}`).prop('checked', !isActive);
            }
        });
    });

    // Inicializar configurações padrão
    $('#inicializarConfigs').on('click', function() {
        const $btn = $(this);
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Inicializando...');
        
        $.ajax({
            url: '{{ route("admin.configuracoes.inicializar") }}',
            method: 'POST',
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
                toastr.error('Erro ao inicializar configurações');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush
