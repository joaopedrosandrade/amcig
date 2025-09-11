<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização da Solicitação - AMCIG</title>
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
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .status-aberta { background-color: #007bff; color: white; }
        .status-em_analise { background-color: #ffc107; color: #856404; }
        .status-em_andamento { background-color: #17a2b8; color: white; }
        .status-concluida { background-color: #28a745; color: white; }
        .status-cancelada { background-color: #6c757d; color: white; }
        .status-rejeitada { background-color: #dc3545; color: white; }
        
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
        
        .status-change {
            background-color: #e8f5e8;
            border: 1px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .status-change h4 {
            color: #155724;
            margin-bottom: 10px;
        }
        
        .admin-notes {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .admin-notes h4 {
            color: #856404;
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
                🔄 Atualização da sua Solicitação
            </div>
            
            <div class="message">
                <p>Olá <strong>{{ $solicitacao->user->name }}</strong>,</p>
                
                <p>Sua solicitação foi atualizada pela equipe da <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong>.</p>
            </div>
            
            <div class="status-change">
                <h4>📊 Mudança de Status</h4>
                @if($statusAnterior)
                    <p><strong>Status anterior:</strong> 
                        <span class="status-badge status-{{ strtolower(str_replace('_', '_', $statusAnterior)) }}">
                            {{ $statusAnterior == 'EM_ANALISE' ? 'Em Análise' : ($statusAnterior == 'EM_ANDAMENTO' ? 'Em Andamento' : ucfirst(strtolower($statusAnterior))) }}
                        </span>
                    </p>
                @endif
                <p><strong>Status atual:</strong> 
                    <span class="status-badge status-{{ strtolower(str_replace('_', '_', $solicitacao->status)) }}">
                        {{ $solicitacao->status_nome }}
                    </span>
                </p>
            </div>
            
            <div class="info-box">
                <h3>📋 Detalhes da solicitação:</h3>
                <p><strong>Número:</strong> #{{ $solicitacao->id }}</p>
                <p><strong>Título:</strong> {{ $solicitacao->titulo }}</p>
                <p><strong>Tipo:</strong> {{ $solicitacao->tipo_nome }}</p>
                <p><strong>Prioridade:</strong> 
                    <span class="priority-badge priority-{{ strtolower($solicitacao->prioridade) }}">
                        {{ $solicitacao->prioridade_nome }}
                    </span>
                </p>
                @if($solicitacao->admin)
                    <p><strong>Responsável:</strong> {{ $solicitacao->admin->name }}</p>
                @endif
                @if($solicitacao->data_limite)
                    <p><strong>Data limite:</strong> {{ $solicitacao->data_limite ? $solicitacao->data_limite->format('d/m/Y H:i') : 'N/A' }}</p>
                @endif
                @if($solicitacao->data_conclusao)
                    <p><strong>Data de conclusão:</strong> {{ $solicitacao->data_conclusao ? $solicitacao->data_conclusao->format('d/m/Y H:i') : 'N/A' }}</p>
                @endif
            </div>
            
            @if($solicitacao->observacoes_admin)
                <div class="admin-notes">
                    <h4>💬 Observações da Administração</h4>
                    <p>{{ $solicitacao->observacoes_admin }}</p>
                </div>
            @endif
            
            @switch($solicitacao->status)
                @case('EM_ANALISE')
                    <div class="message">
                        <p><strong>📋 Status: Em Análise</strong></p>
                        <p>Sua solicitação está sendo analisada pela nossa equipe. Em breve você receberá mais informações sobre o andamento.</p>
                    </div>
                    @break
                @case('EM_ANDAMENTO')
                    <div class="message">
                        <p><strong>⚙️ Status: Em Andamento</strong></p>
                        <p>Nossa equipe está trabalhando na resolução da sua solicitação. Você será informado sobre qualquer atualização importante.</p>
                    </div>
                    @break
                @case('CONCLUIDA')
                    <div class="message">
                        <p><strong>✅ Status: Concluída</strong></p>
                        <p>Parabéns! Sua solicitação foi concluída com sucesso. Agradecemos sua participação e contribuição para melhorar nossa comunidade.</p>
                    </div>
                    @break
                @case('CANCELADA')
                    <div class="message">
                        <p><strong>❌ Status: Cancelada</strong></p>
                        <p>Sua solicitação foi cancelada. Se você tiver dúvidas sobre o motivo, entre em contato conosco.</p>
                    </div>
                    @break
                @case('REJEITADA')
                    <div class="message">
                        <p><strong>🚫 Status: Rejeitada</strong></p>
                        <p>Sua solicitação foi rejeitada. Se você tiver dúvidas sobre o motivo, entre em contato conosco para mais esclarecimentos.</p>
                    </div>
                    @break
            @endswitch
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('associado.solicitacoes.show', $solicitacao->id) }}" class="cta-button">
                    Ver Detalhes da Solicitação
                </a>
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
