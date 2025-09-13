<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Presença - {{ $evento->titulo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .event-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <div class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1><i class="ri-calendar-event-line me-2"></i>Lista de Presença</h1>
                    <p class="lead mb-0">Registre sua presença no evento</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="event-info">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="mb-2">{{ $evento->titulo }}</h4>
                            @if($evento->descricao)
                                <p class="text-muted mb-2">{{ $evento->descricao }}</p>
                            @endif
                            <div class="d-flex flex-wrap gap-3">
                                <span class="badge bg-primary">
                                    <i class="ri-calendar-line me-1"></i>{{ $evento->data_evento->format('d/m/Y') }}
                                </span>
                                <span class="badge bg-info">
                                    <i class="ri-time-line me-1"></i>{{ $evento->hora_inicio }}
                                    @if($evento->hora_fim)
                                        - {{ $evento->hora_fim }}
                                    @endif
                                </span>
                                <span class="badge bg-success">
                                    <i class="ri-map-pin-line me-1"></i>{{ $evento->local }}
                                </span>
                                <span class="badge bg-warning">
                                    <i class="ri-group-line me-1"></i>{{ ucfirst($evento->tipo) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="alert alert-success mb-0">
                                <i class="ri-check-line me-2"></i>
                                <strong>Lista Ativa</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-container">
                    <h5 class="mb-4"><i class="ri-user-add-line me-2"></i>Registrar Presença</h5>
                    
                    <form id="presencaForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="000.000.000-00">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Observações adicionais (opcional)"></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ri-check-line me-2"></i>Confirmar Presença
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="ri-shield-check-line me-1"></i>
                        Seus dados estão seguros e serão utilizados apenas para controle de presença.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <script>
        // Configuração do Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $(document).ready(function() {
            // Máscaras para CPF e telefone
            $('#cpf').mask('000.000.000-00');
            $('#telefone').mask('(00) 00000-0000');

            // Buscar usuário por CPF
            $('#cpf').on('blur', function() {
                const cpf = $(this).val().replace(/[^0-9]/g, '');
                if (cpf.length === 11) {
                    $.ajax({
                        url: '{{ route("evento.buscar-usuario") }}',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            cpf: cpf
                        },
                        success: function(response) {
                            if (response.success && response.user) {
                                $('#nome').val(response.user.nome);
                                $('#email').val(response.user.email);
                                $('#telefone').val(response.user.telefone);
                                toastr.info('Dados preenchidos automaticamente!');
                            }
                        }
                    });
                }
            });

            // Formulário de presença
            $('#presencaForm').on('submit', function(e) {
                e.preventDefault();
                
                const $btn = $(this).find('button[type="submit"]');
                const originalText = $btn.html();
                
                // Desabilitar botão e mostrar loading
                $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-2"></i>Registrando...');
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: '{{ route("evento.presenca.store", $evento->link_presenca) }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#presencaForm')[0].reset();
                            // Opcional: redirecionar ou mostrar mensagem de sucesso
                            setTimeout(function() {
                                $('<div class="alert alert-success text-center"><i class="ri-check-circle-line me-2"></i><strong>Presença registrada com sucesso!</strong><br>Sua presença foi confirmada no evento.</div>').insertAfter('.form-container');
                                $('.form-container').hide();
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = 'Erro de validação:\n';
                            
                            Object.keys(errors).forEach(function(key) {
                                errorMessage += '- ' + errors[key][0] + '\n';
                            });
                            
                            toastr.error(errorMessage);
                        } else {
                            const errorMsg = xhr.responseJSON?.message || 'Erro ao registrar presença';
                            toastr.error(errorMsg);
                        }
                    },
                    complete: function() {
                        // Reabilitar botão
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
</body>
</html>
