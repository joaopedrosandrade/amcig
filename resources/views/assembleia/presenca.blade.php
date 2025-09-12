<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença - {{ $assembleia->titulo }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 2rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .info-card {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .loading {
            display: none;
        }
        .loading.show {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card">
                        <div class="card-header text-center">
                            <h2 class="mb-3">
                                <i class="bi bi-calendar-check me-2"></i>
                                Lista de Presença
                            </h2>
                            <h4 class="mb-2">{{ $assembleia->titulo }}</h4>
                            <p class="mb-0">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $assembleia->data_assembleia->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($assembleia->hora_inicio)->format('H:i') }}
                                <br>
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $assembleia->local }}
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <div class="info-card">
                                <h5 class="text-primary mb-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informações da Assembleia
                                </h5>
                                <p class="mb-2">
                                    <strong>Tipo:</strong> {{ ucfirst($assembleia->tipo) }}
                                </p>
                                @if($assembleia->descricao)
                                    <p class="mb-0">
                                        <strong>Descrição:</strong> {{ $assembleia->descricao }}
                                    </p>
                                @endif
                            </div>

                            <form id="presencaForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">
                                                <i class="bi bi-person me-1"></i>
                                                Nome Completo <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nome" name="nome" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cpf" class="form-label">
                                                <i class="bi bi-card-text me-1"></i>
                                                CPF <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="000.000.000-00">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                <i class="bi bi-envelope me-1"></i>
                                                Email
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">
                                                <i class="bi bi-telephone me-1"></i>
                                                Telefone
                                            </label>
                                            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="observacoes" class="form-label">
                                        <i class="bi bi-chat-text me-1"></i>
                                        Observações
                                    </label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Observações adicionais (opcional)"></textarea>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <span class="btn-text">Registrar Presença</span>
                                        <span class="loading">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Registrando...
                                        </span>
                                    </button>
                                </div>
                            </form>

                            <div class="alert alert-success mt-3" id="successAlert" style="display: none;">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Sucesso!</strong> Sua presença foi registrada com sucesso.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
    $(document).ready(function() {
        // Configurar máscaras
        $('#cpf').mask('000.000.000-00');
        $('#telefone').mask('(00) 00000-0000');

        // Buscar usuário por CPF
        $('#cpf').on('blur', function() {
            const cpf = $(this).val().replace(/[^0-9]/g, '');
            
            if (cpf.length === 11) {
                $.ajax({
                    url: '{{ route("assembleia.buscar-usuario") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cpf: cpf
                    },
                    success: function(response) {
                        if (response.success && response.user) {
                            $('#nome').val(response.user.nome);
                            $('#email').val(response.user.email);
                            $('#telefone').val(response.user.telefone);
                            toastr.info('Dados preenchidos automaticamente');
                        }
                    }
                });
            }
        });

        // Submissão do formulário
        $('#presencaForm').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $(this).find('button[type="submit"]');
            const $btnText = $btn.find('.btn-text');
            const $loading = $btn.find('.loading');
            
            // Mostrar loading
            $btnText.hide();
            $loading.addClass('show');
            $btn.prop('disabled', true);
            
            $.ajax({
                url: '{{ route("assembleia.presenca.store", $assembleia->link_presenca) }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#presencaForm').hide();
                        $('#successAlert').show();
                        toastr.success(response.message);
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
                        toastr.error(xhr.responseJSON.message || 'Erro ao registrar presença');
                    }
                },
                complete: function() {
                    // Esconder loading
                    $btnText.show();
                    $loading.removeClass('show');
                    $btn.prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>
