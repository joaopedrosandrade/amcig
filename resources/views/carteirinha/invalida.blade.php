<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteirinha Inválida - AMCIG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            border: 1px solid #e9ecef;
        }
        
        .icon {
            width: 80px;
            height: 80px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 40px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        .matricula {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #495057;
        }
        
        .message {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
        
        @media (max-width: 768px) {
            .card {
                padding: 30px 20px;
            }
            
            .title {
                font-size: 20px;
            }
            
            .subtitle {
                font-size: 14px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="{{ asset('assets/images/logo-md.png') }}" alt="AMCIG Logo">
            </div>
            
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <div class="title">Carteirinha Inválida</div>
            
            <div class="subtitle">AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri-ES</div>
            
            <div class="matricula">
                Matrícula: {{ $matricula }}
            </div>
            
            <div class="message">
                @if(isset($motivo) && $motivo === 'nao_aprovado')
                    Esta matrícula pertence a um associado que ainda não foi aprovado.<br>
                    Entre em contato com a administração para mais informações.
                @else
                    Esta matrícula não foi encontrada em nosso sistema.<br>
                    Verifique se o número está correto ou entre em contato conosco.
                @endif
            </div>
            
            <a href="{{ route('associado.index') }}" class="btn">
                <i class="fas fa-home me-2"></i>
                Voltar ao Início
            </a>
        </div>
    </div>
</body>
</html>
