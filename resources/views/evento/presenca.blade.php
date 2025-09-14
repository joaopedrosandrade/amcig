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
        .tipo-participante {
            transition: all 0.3s ease;
        }
        .tipo-participante:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .tipo-participante.border-primary {
            border-color: #667eea !important;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
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
                    </div>
                </div>

                <div class="form-container">
                    <h5 class="mb-4"><i class="ri-user-add-line me-2"></i>Registrar Presença</h5>
                    
                    <!-- Seleção do tipo de participante -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Clique sobre uma opção: <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-2 tipo-participante" data-tipo="associado" style="cursor: pointer;">
                                    <div class="card-body text-center">
                                      
                                        <h6 class="card-title">Sou Associado</h6>
                                        <p class="card-text text-muted small">Digite seu CPF para preenchimento automático</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-2 tipo-participante" data-tipo="visitante" style="cursor: pointer;">
                                    <div class="card-body text-center">
                                     
                                        <h6 class="card-title">Não Sou Associado</h6>
                                        <p class="card-text text-muted small">Preencha seus dados manualmente</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário para Associado -->
                    <form id="presencaFormAssociado" style="display: none;">
                        @csrf
                        <input type="hidden" name="tipo" value="associado">
                        
                        <div class="mb-3">
                            <label for="cpf_associado" class="form-label">CPF <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="cpf_associado" name="cpf" required placeholder="000.000.000-00">
                                <button type="button" class="btn btn-outline-primary" id="buscarAssociado">
                                    <i class="ri-search-line me-1"></i>Buscar
                                </button>
                            </div>
                            <div class="form-text">Digite seu CPF e clique em "Buscar" para preencher seus dados automaticamente</div>
                        </div>
                        
                        <div id="dadosAssociado" style="display: none;">
                            <div class="alert alert-success">
                                <i class="ri-check-line me-2"></i>
                                <strong>Dados encontrados!</strong> Verifique as informações abaixo:
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nome_associado" class="form-label">Nome Completo</label>
                                        <input type="text" class="form-control" id="nome_associado" name="nome" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_associado" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="email_associado" name="email" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone_associado" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone_associado" name="telefone" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="observacoes_associado" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoes_associado" name="observacoes" rows="3" placeholder="Observações adicionais (opcional)"></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ri-check-line me-2"></i>Confirmar Presença
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Formulário para Visitante -->
                    <form id="presencaFormVisitante" style="display: none;">
                        @csrf
                        <input type="hidden" name="tipo" value="visitante">
                        
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Que tal se tornar um associado?</strong> 
                            <a href="#" class="alert-link">Clique aqui para saber mais sobre os benefícios</a>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nome_visitante" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nome_visitante" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cpf_visitante" class="form-label">CPF <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cpf_visitante" name="cpf" required placeholder="000.000.000-00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefone_visitante" class="form-label">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telefone_visitante" name="telefone" required placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg">
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
            $('#cpf_associado, #cpf_visitante').mask('000.000.000-00');
            $('#telefone_visitante').mask('(00) 00000-0000');

            // Seleção do tipo de participante
            $('.tipo-participante').on('click', function() {
                const tipo = $(this).data('tipo');
                
                // Remover seleção anterior
                $('.tipo-participante').removeClass('border-primary').addClass('border-secondary');
                $(this).removeClass('border-secondary').addClass('border-primary');
                
                // Esconder formulários
                $('#presencaFormAssociado, #presencaFormVisitante').hide();
                
                // Mostrar formulário selecionado
                if (tipo === 'associado') {
                    $('#presencaFormAssociado').show();
                } else {
                    $('#presencaFormVisitante').show();
                }
            });

            // Buscar usuário associado por CPF
            $('#buscarAssociado').on('click', function() {
                const cpf = $('#cpf_associado').val().replace(/[^0-9]/g, '');
                const $btn = $(this);
                
                if (cpf.length !== 11) {
                    toastr.warning('Por favor, digite um CPF válido (11 dígitos)');
                    $('#cpf_associado').focus();
                    return;
                }
                
                // Desabilita o botão e mostra loading
                $btn.prop('disabled', true);
                $btn.html('<i class="ri-loader-4-line ri-spin me-1"></i>Buscando...');
                
                // Primeiro verifica se já está registrado
                $.ajax({
                    url: '{{ route("evento.verificar-duplicacao") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        cpf: cpf,
                        link: '{{ $evento->link_presenca }}'
                    },
                    success: function(response) {
                        if (response.duplicado) {
                            $('#dadosAssociado').hide();
                            toastr.error('Você já está registrado neste evento! Não é possível confirmar presença novamente.');
                            $('#cpf_associado').val('').focus();
                            resetButton();
                            return;
                        }
                        
                        // Se não está duplicado, busca os dados do usuário
                        $.ajax({
                            url: '{{ route("evento.buscar-usuario") }}',
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                cpf: cpf
                            },
                            success: function(response) {
                                if (response.success && response.user) {
                                    $('#nome_associado').val(response.user.nome);
                                    $('#email_associado').val(response.user.email);
                                    $('#telefone_associado').val(response.user.telefone);
                                    $('#dadosAssociado').show();
                                    toastr.success('Dados encontrados! Verifique as informações abaixo.');
                                } else {
                                    $('#dadosAssociado').hide();
                                    toastr.warning('CPF não encontrado. Verifique se você está cadastrado como associado.');
                                }
                                resetButton();
                            },
                            error: function() {
                                $('#dadosAssociado').hide();
                                toastr.error('Erro ao buscar dados do associado.');
                                resetButton();
                            }
                        });
                    },
                    error: function() {
                        $('#dadosAssociado').hide();
                        toastr.error('Erro ao verificar duplicação.');
                        resetButton();
                    }
                });
                
                function resetButton() {
                    $btn.prop('disabled', false);
                    $btn.html('<i class="ri-search-line me-1"></i>Buscar');
                }
            });

            // Permitir busca com Enter no campo CPF
            $('#cpf_associado').on('keypress', function(e) {
                if (e.which === 13) { // Enter
                    e.preventDefault();
                    $('#buscarAssociado').click();
                }
            });

            // Formulário de presença para associado
            $('#presencaFormAssociado').on('submit', function(e) {
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
                            mostrarSucesso();
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
                            if (errorMsg.includes('já está registrado')) {
                                toastr.warning(errorMsg);
                            } else {
                                toastr.error(errorMsg);
                            }
                        }
                    },
                    complete: function() {
                        // Reabilitar botão
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Formulário de presença para visitante
            $('#presencaFormVisitante').on('submit', function(e) {
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
                            mostrarSucesso();
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
                            if (errorMsg.includes('já está registrado')) {
                                toastr.warning(errorMsg);
                            } else {
                                toastr.error(errorMsg);
                            }
                        }
                    },
                    complete: function() {
                        // Reabilitar botão
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Função para mostrar mensagem de sucesso
            function mostrarSucesso() {
                setTimeout(function() {
                    $('<div class="alert alert-success text-center mt-4"><i class="ri-check-circle-line me-2"></i><strong>Presença registrada com sucesso!</strong><br>Sua presença foi confirmada no evento.<br><small class="text-muted">Obrigado por participar!</small></div>').insertAfter('.form-container');
                    $('.form-container').hide();
                }, 1000);
            }
        });
    </script>
</body>
</html>
