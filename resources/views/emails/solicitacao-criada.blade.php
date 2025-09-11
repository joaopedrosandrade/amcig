<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação Recebida - AMCIG</title>
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
        
        .status-badge {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .priority-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .priority-baixa { background-color: #28a745; color: white; }
        .priority-media { background-color: #007bff; color: white; }
        .priority-alta { background-color: #ffc107; color: #856404; }
        .priority-urgente { background-color: #dc3545; color: white; }
        
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
        
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }
        
        .location-info {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .location-info h4 {
            color: #1976d2;
            margin-bottom: 10px;
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
            <img src="{{ asset('assets/images/logo-md.png') }}" alt="Logo AMCIG" class="logo">
            <h1>AMCIG</h1>
            <p>Associação de Moradores e Comerciantes da Ilha de Guriri</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="welcome-message">
                📋 Solicitação Recebida com Sucesso!
            </div>
            
            <div class="message">
                <p>Olá <strong>{{ $solicitacao->user->name }}</strong>,</p>
                
                <p>Sua solicitação foi recebida pela <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong> e está sendo processada pela nossa equipe.</p>
            </div>
            
            <div class="info-box">
                <h3>📋 Detalhes da sua solicitação:</h3>
                <p><strong>Número:</strong> #{{ $solicitacao->id }}</p>
                <p><strong>Título:</strong> {{ $solicitacao->titulo }}</p>
                <p><strong>Tipo:</strong> {{ $solicitacao->tipo_nome }}</p>
                <p><strong>Prioridade:</strong> 
                    <span class="priority-badge priority-{{ strtolower($solicitacao->prioridade) }}">
                        {{ $solicitacao->prioridade_nome }}
                    </span>
                </p>
                <p><strong>Status:</strong> <span class="status-badge">{{ $solicitacao->status_nome }}</span></p>
                <p><strong>Data de criação:</strong> {{ $solicitacao->created_at ? $solicitacao->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
            </div>
            
            <div class="message">
                <p><strong>Descrição da solicitação:</strong></p>
                <p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    {{ $solicitacao->descricao }}
                </p>
            </div>
            
            @if($solicitacao->endereco)
                <div class="location-info">
                    <h4>📍 Localização</h4>
                    <p><strong>Endereço:</strong> {{ $solicitacao->endereco }}</p>
                    @if($solicitacao->bairro)
                        <p><strong>Bairro:</strong> {{ $solicitacao->bairro }}</p>
                    @endif
                    @if($solicitacao->cep)
                        <p><strong>CEP:</strong> {{ $solicitacao->cep }}</p>
                    @endif
                </div>
            @endif
            
            <div class="message">
                <p><strong>Próximos passos:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Nossa equipe analisará sua solicitação</li>
                    <li>Você receberá atualizações por email sobre o andamento</li>
                    <li>Se necessário, nossa equipe entrará em contato para mais informações</li>
                    <li>Você pode acompanhar o status através da sua área do associado</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                @if($solicitacao->id && $solicitacao->id > 0)
                    <a href="{{ route('associado.solicitacoes.show', $solicitacao->id) }}" class="cta-button">
                        Acompanhar Solicitação
                    </a>
                @else
                    <a href="{{ route('associado.solicitacoes.index') }}" class="cta-button">
                        Ver Minhas Solicitações
                    </a>
                @endif
            </div>
            
            <div class="divider"></div>
            
            <div class="message">
                <p><strong>Importante:</strong> Mantenha este email em segurança, pois ele contém informações importantes sobre sua solicitação.</p>
                
                <p>Se você tiver alguma dúvida ou precisar de mais informações, não hesite em entrar em contato conosco através dos canais oficiais da AMCIG.</p>
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
