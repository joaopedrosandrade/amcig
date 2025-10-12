<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Negado - AMCIG Admin</title>
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        .btn-secondary-custom {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e9ecef;
        }
        .btn-secondary-custom:hover {
            background: #e9ecef;
            color: #333;
            transform: translateY(-2px);
        }
        .error-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="ri-shield-cross-line"></i>
        </div>
        
        <h1 class="error-title">403</h1>
        
        <p class="error-message">
            <strong>Acesso Negado</strong><br>
            Você não tem permissão para acessar este módulo ou realizar esta ação.
        </p>
        
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('admin.dashboard') }}" class="btn-custom btn-primary-custom">
                <i class="ri-home-line me-1"></i> Ir para Dashboard
            </a>
            <a href="{{ route('admin.config.usuarios.index') }}" class="btn-custom btn-secondary-custom">
                <i class="ri-arrow-left-line me-1"></i> Voltar
            </a>
        </div>
        
        @if(config('app.debug'))
            <div class="error-details">
                <strong>Detalhes do Erro:</strong><br>
                <small>
                    Código: 403 Forbidden<br>
                    Mensagem: {{ $exception->getMessage() ?? 'Acesso negado' }}<br>
                    @if(isset($exception) && $exception->getFile())
                        Arquivo: {{ basename($exception->getFile()) }}:{{ $exception->getLine() }}
                    @endif
                </small>
            </div>
        @endif
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
