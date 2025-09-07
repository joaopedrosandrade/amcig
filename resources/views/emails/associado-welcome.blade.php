<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à AMCIG!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
            color: #333;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-message {
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            text-align: justify;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .info-box h3 {
            color: #007bff;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .info-box p {
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .info-box strong {
            color: #0056b3;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            background-color: #ffc107;
            color: #856404;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 5px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .header {
                padding: 20px 15px;
            }
            
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('assets/images/logos.png') }}" alt="Logo AMCIG" class="logo">
            <h1>AMCIG</h1>
            <p>Associação de Moradores e Comerciantes da Ilha de Guriri</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="welcome-message">
                🎉 Bem-vindo(a) à AMCIG, {{ $user->name }}!
            </div>
            
            <div class="message">
                <p>É com grande alegria que recebemos seu cadastro na <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong>!</p>
                
                <p>Seu cadastro foi realizado com sucesso e está sendo analisado pela nossa equipe. Em breve você receberá informações sobre o status da sua aprovação.</p>
            </div>
            
            <div class="info-box">
                <h3>📋 Informações do seu cadastro:</h3>
                <p><strong>Nome:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>CPF:</strong> {{ $user->cpf }}</p>
                <p><strong>Tipo de Associado:</strong> 
                    @if($user->tipo_associado == 'morador')
                        Morador
                    @elseif($user->tipo_associado == 'comerciante')
                        Comerciante
                    @else
                        Morador e Comerciante
                    @endif
                </p>
                @if($user->nome_comercio)
                    <p><strong>Nome do Comércio:</strong> {{ $user->nome_comercio }}</p>
                @endif
                <p><strong>Status:</strong> <span class="status-badge">PENDENTE DE APROVAÇÃO</span></p>
            </div>
            
            <div class="message">
                <p><strong>Próximos passos:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Nossa equipe analisará seu cadastro</li>
                    <li>Você receberá um email de confirmação quando for aprovado</li>
                    <li>Após a aprovação, você terá acesso à sua carteirinha virtual</li>
                    <li>Você poderá participar de todas as atividades da associação</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('associado.success') }}" class="cta-button">
                    Acessar Área do Associado
                </a>
            </div>
            
            <div class="divider"></div>
            
            <div class="message">
                <p><strong>Importante:</strong> Mantenha este email em segurança, pois ele contém informações importantes sobre seu cadastro.</p>
                
                <p>Se você tiver alguma dúvida ou precisar de ajuda, não hesite em entrar em contato conosco através dos canais oficiais da AMCIG.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri</strong></p>
            <p>📍 São Mateus - ES | 📧 contato@amcig.com.br</p>
            
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">WhatsApp</a>
            </div>
            
            <div style="margin-top: 20px; font-size: 12px; color: #adb5bd;">
                <p>Este é um email automático, por favor não responda diretamente.</p>
                <p>© {{ date('Y') }} AMCIG. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
