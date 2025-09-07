<?php

namespace App\Services;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Illuminate\Support\Facades\Log;

class EmailService
{
    private $transport;
    private $mailer;

    public function __construct()
    {
        // Configurar transporte SMTP usando Mailgun (confiável para entrega)
        $this->transport = (new Swift_SmtpTransport('smtp.mailgun.org', 587, 'tls'))
            ->setUsername('associe@amcig.online')
            ->setPassword('1602Jpsa*');

        $this->mailer = new Swift_Mailer($this->transport);
    }

    /**
     * Enviar email de boas-vindas para associado
     *
     * @param string $email
     * @param string $nome
     * @param array $dadosAssociado
     * @return bool
     */
    public function enviarEmailBoasVindas($email, $nome, $dadosAssociado)
    {
        try {
            Log::info("Tentando enviar email para: {$email}");
            Log::info("Usando SwiftMailer com servidor: smtp.mailgun.org:587 (TLS)");

            // Criar mensagem
            $message = (new Swift_Message('Bem-vindo à AMCIG - Cadastro Realizado com Sucesso!'))
                ->setFrom(['contato@amcig.online' => 'AMCIG - Associação de Moradores e Comerciantes'])
                ->setTo([$email => $nome])
                ->setBody($this->gerarTemplateEmail($dadosAssociado), 'text/html')
                ->setCharset('utf-8')
                ->setReplyTo(['contato@amcig.online' => 'AMCIG'])
                ->setReturnPath('contato@amcig.online');

            // Enviar email
            $result = $this->mailer->send($message);

            if ($result) {
                Log::info("Email enviado com sucesso para: {$email}");
                return true;
            } else {
                Log::error("Falha ao enviar email para: {$email}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Erro ao enviar email para {$email}: " . $e->getMessage());
            Log::error("Detalhes do erro: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Enviar email de aprovação para associado
     *
     * @param string $email
     * @param string $nome
     * @param array $dadosAssociado
     * @return bool
     */
    public function enviarEmailAprovacao($email, $nome, $dadosAssociado)
    {
        try {
            Log::info("Tentando enviar email de aprovação para: {$email}");

            // Criar mensagem
            $message = (new Swift_Message('🎉 Parabéns! Você foi aprovado na AMCIG!'))
                ->setFrom(['contato@amcig.online' => 'AMCIG - Associação de Moradores e Comerciantes'])
                ->setTo([$email => $nome])
                ->setBody($this->gerarTemplateEmailAprovacao($dadosAssociado), 'text/html')
                ->setCharset('utf-8')
                ->setReplyTo(['contato@amcig.online' => 'AMCIG'])
                ->setReturnPath('contato@amcig.online');

            // Enviar email
            $result = $this->mailer->send($message);

            if ($result) {
                Log::info("Email de aprovação enviado com sucesso para: {$email}");
                return true;
            } else {
                Log::error("Falha ao enviar email de aprovação para: {$email}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Erro ao enviar email de aprovação para {$email}: " . $e->getMessage());
            Log::error("Detalhes do erro: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Enviar email de rejeição para associado
     *
     * @param string $email
     * @param string $nome
     * @param array $dadosAssociado
     * @return bool
     */
    public function enviarEmailRejeicao($email, $nome, $dadosAssociado)
    {
        try {
            Log::info("Tentando enviar email de rejeição para: {$email}");

            // Criar mensagem
            $message = (new Swift_Message('AMCIG - Informações sobre seu cadastro'))
                ->setFrom(['contato@amcig.online' => 'AMCIG - Associação de Moradores e Comerciantes'])
                ->setTo([$email => $nome])
                ->setBody($this->gerarTemplateEmailRejeicao($dadosAssociado), 'text/html')
                ->setCharset('utf-8')
                ->setReplyTo(['contato@amcig.online' => 'AMCIG'])
                ->setReturnPath('contato@amcig.online');

            // Enviar email
            $result = $this->mailer->send($message);

            if ($result) {
                Log::info("Email de rejeição enviado com sucesso para: {$email}");
                return true;
            } else {
                Log::error("Falha ao enviar email de rejeição para: {$email}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Erro ao enviar email de rejeição para {$email}: " . $e->getMessage());
            Log::error("Detalhes do erro: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Gerar template HTML do email
     *
     * @param array $dadosAssociado
     * @return string
     */
    private function gerarTemplateEmail($dadosAssociado)
    {
        $tipoAssociado = $dadosAssociado['tipo_associado'] == 'morador' ? 'Morador' : 'Comerciante';
        
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bem-vindo à AMCIG!</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; background-color: #f8f9fa; line-height: 1.6; color: #333; }
                .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 30px 20px; text-align: center; }
                .logo { max-width: 120px; height: auto; margin-bottom: 15px; }
                .header h1 { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
                .header p { font-size: 14px; opacity: 0.9; }
                .content { padding: 40px 30px; }
                .welcome-message { font-size: 18px; color: #007bff; font-weight: bold; margin-bottom: 20px; text-align: center; }
                .message { font-size: 16px; margin-bottom: 25px; text-align: justify; }
                .info-box { background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 20px; margin: 25px 0; border-radius: 0 5px 5px 0; }
                .info-box h3 { color: #007bff; font-size: 16px; margin-bottom: 10px; }
                .info-box p { font-size: 14px; margin-bottom: 8px; }
                .info-box strong { color: #0056b3; }
                .status-badge { display: inline-block; background-color: #ffc107; color: #856404; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 10px 0; }
                .footer { background-color: #f8f9fa; padding: 30px 20px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { font-size: 14px; color: #6c757d; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <img src="' . asset('assets/images/logo-md.png') . '" alt="Logo AMCIG" class="logo">
                    <h1>AMCIG</h1>
                    <p>Associação de Moradores e Comerciantes da Ilha de Guriri</p>
                </div>
                
                <div class="content">
                    <div class="welcome-message">
                        🎉 Bem-vindo(a) à AMCIG, ' . htmlspecialchars($dadosAssociado['nome']) . '!
                    </div>
                    
                    <div class="message">
                        <p>É com grande alegria que recebemos seu cadastro na <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong>!</p>
                        <p>Seu cadastro foi realizado com sucesso e está sendo analisado pela nossa equipe. Em breve você receberá informações sobre o status da sua aprovação.</p>
                    </div>
                    
                    <div class="info-box">
                        <h3>📋 Informações do seu cadastro:</h3>
                        <p><strong>Nome:</strong> ' . htmlspecialchars($dadosAssociado['nome']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($dadosAssociado['email']) . '</p>
                        <p><strong>CPF:</strong> ' . htmlspecialchars($dadosAssociado['cpf']) . '</p>
                        <p><strong>Tipo de Associado:</strong> ' . $tipoAssociado . '</p>
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
                    
                    <div class="message">
                        <p><strong>Importante:</strong> Mantenha este email em segurança, pois ele contém informações importantes sobre seu cadastro.</p>
                        <p>Se você tiver alguma dúvida ou precisar de ajuda, não hesite em entrar em contato conosco através dos canais oficiais da AMCIG.</p>
                    </div>
                </div>
                
                <div class="footer">
                    <p><strong>AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri</strong></p>
                    <p>📍 São Mateus - ES | 📧 contato@amcig.online</p>
                    <p>© ' . date('Y') . ' AMCIG. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Gerar template HTML do email de aprovação
     *
     * @param array $dadosAssociado
     * @return string
     */
    private function gerarTemplateEmailAprovacao($dadosAssociado)
    {
        $tipoAssociado = $dadosAssociado['tipo_associado'] == 'morador' ? 'Morador' : 'Comerciante';
        $dataAprovacao = $dadosAssociado['data_aprovacao'] ? $dadosAssociado['data_aprovacao']->format('d/m/Y H:i') : date('d/m/Y H:i');
        
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Parabéns! Você foi aprovado na AMCIG!</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; background-color: #f8f9fa; line-height: 1.6; color: #333; }
                .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px 20px; text-align: center; }
                .logo { max-width: 120px; height: auto; margin-bottom: 15px; }
                .header h1 { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
                .header p { font-size: 14px; opacity: 0.9; }
                .content { padding: 40px 30px; }
                .welcome-message { font-size: 18px; color: #28a745; font-weight: bold; margin-bottom: 20px; text-align: center; }
                .message { font-size: 16px; margin-bottom: 25px; text-align: justify; }
                .info-box { background-color: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 25px 0; border-radius: 0 5px 5px 0; }
                .info-box h3 { color: #28a745; font-size: 16px; margin-bottom: 10px; }
                .info-box p { font-size: 14px; margin-bottom: 8px; }
                .info-box strong { color: #155724; }
                .status-badge { display: inline-block; background-color: #28a745; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 10px 0; }
                .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; text-align: center; margin: 20px 0; }
                .cta-button:hover { transform: translateY(-2px); color: white; text-decoration: none; }
                .footer { background-color: #f8f9fa; padding: 30px 20px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { font-size: 14px; color: #6c757d; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>🎉 PARABÉNS!</h1>
                    <p>Você foi aprovado na AMCIG!</p>
                </div>
                
                <div class="content">
                    <div class="welcome-message">
                        🎊 Bem-vindo(a) oficialmente à AMCIG, ' . htmlspecialchars($dadosAssociado['nome']) . '!
                    </div>
                    
                    <div class="message">
                        <p>É com grande alegria que informamos que seu cadastro foi <strong>APROVADO</strong> pela nossa equipe!</p>
                        <p>Agora você é oficialmente um(a) associado(a) da <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong>!</p>
                    </div>
                    
                    <div class="info-box">
                        <h3>✅ Informações da sua aprovação:</h3>
                        <p><strong>Nome:</strong> ' . htmlspecialchars($dadosAssociado['nome']) . '</p>
                        <p><strong>Matrícula:</strong> ' . htmlspecialchars($dadosAssociado['matricula']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($dadosAssociado['email']) . '</p>
                        <p><strong>CPF:</strong> ' . htmlspecialchars($dadosAssociado['cpf']) . '</p>
                        <p><strong>Tipo de Associado:</strong> ' . $tipoAssociado . '</p>
                        <p><strong>Data de Aprovação:</strong> ' . $dataAprovacao . '</p>
                        <p><strong>Status:</strong> <span class="status-badge">APROVADO</span></p>
                    </div>
                    
                    <div class="message">
                        <p><strong>🎯 O que você pode fazer agora:</strong></p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Acessar sua carteirinha virtual</li>
                            <li>Participar de todas as atividades da associação</li>
                            <li>Ter acesso aos benefícios exclusivos</li>
                            <li>Participar das reuniões e eventos</li>
                            <li>Votar nas decisões da associação</li>
                        </ul>
                    </div>
                    
                    <div class="info-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
                        <h3 style="color: #856404;">💳 IMPORTANTE - Pagamento da Mensalidade</h3>
                        <p style="color: #856404;"><strong>Sua assinatura foi criada automaticamente!</strong></p>
                        <p style="color: #856404;"><strong>Valor da mensalidade:</strong> R$ ' . number_format($dadosAssociado['valor_mensalidade'], 2, ',', '.') . ' por mês</p>
                        <p style="color: #856404;"><strong>Forma de pagamento:</strong> PIX (mais rápido e seguro)</p>
                        <p style="color: #856404;"><strong>Próximo vencimento:</strong> ' . ($dadosAssociado['proximo_vencimento'] ?? 'Em breve') . '</p>
                        <p style="color: #856404; margin-top: 15px;"><strong>⚠️ Atenção:</strong> Para manter sua associação ativa, é necessário quitar a mensalidade até a data de vencimento.</p>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . route('associado.pagamentos') . '" class="cta-button" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                            💰 Acessar Minhas Mensalidades
                        </a>
                    </div>
                    
                    <div style="text-align: center; margin: 20px 0;">
                        <a href="' . route('carteirinha.show', $dadosAssociado['matricula']) . '" class="cta-button">
                            🎫 Acessar Minha Carteirinha Virtual
                        </a>
                    </div>
                    
                    <div class="message">
                        <p><strong>📞 Precisa de ajuda?</strong></p>
                        <p>Se você tiver alguma dúvida ou precisar de ajuda, entre em contato conosco através dos canais oficiais da AMCIG.</p>
                    </div>
                    
                    <div class="info-box" style="background-color: #e7f3ff; border-left-color: #007bff;">
                        <h3 style="color: #004085;">📋 Instruções para Pagamento</h3>
                        <p style="color: #004085;"><strong>Como pagar sua mensalidade:</strong></p>
                        <ol style="color: #004085; margin-left: 20px; margin-top: 10px;">
                            <li>Acesse o link "Minhas Mensalidades" acima</li>
                            <li>Visualize sua fatura pendente</li>
                            <li>Escaneie o QR Code PIX com seu aplicativo bancário</li>
                            <li>Ou copie a chave PIX e cole no seu app</li>
                            <li>Confirme o pagamento</li>
                        </ol>
                        <p style="color: #004085; margin-top: 15px;"><strong>💡 Dica:</strong> O pagamento via PIX é instantâneo e você receberá confirmação imediatamente!</p>
                    </div>
                </div>
                
                <div class="footer">
                    <p><strong>AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri</strong></p>
                    <p>📍 São Mateus - ES | 📧 contato@amcig.online</p>
                    <p>© ' . date('Y') . ' AMCIG. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Gerar template HTML do email de rejeição
     *
     * @param array $dadosAssociado
     * @return string
     */
    private function gerarTemplateEmailRejeicao($dadosAssociado)
    {
        $tipoAssociado = $dadosAssociado['tipo_associado'] == 'morador' ? 'Morador' : 'Comerciante';
        $motivoRejeicao = $dadosAssociado['motivo_rejeicao'] ?: 'Não foi informado motivo específico.';
        
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>AMCIG - Informações sobre seu cadastro</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; background-color: #f8f9fa; line-height: 1.6; color: #333; }
                .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 30px 20px; text-align: center; }
                .logo { max-width: 120px; height: auto; margin-bottom: 15px; }
                .header h1 { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
                .header p { font-size: 14px; opacity: 0.9; }
                .content { padding: 40px 30px; }
                .welcome-message { font-size: 18px; color: #dc3545; font-weight: bold; margin-bottom: 20px; text-align: center; }
                .message { font-size: 16px; margin-bottom: 25px; text-align: justify; }
                .info-box { background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin: 25px 0; border-radius: 0 5px 5px 0; }
                .info-box h3 { color: #dc3545; font-size: 16px; margin-bottom: 10px; }
                .info-box p { font-size: 14px; margin-bottom: 8px; }
                .info-box strong { color: #721c24; }
                .status-badge { display: inline-block; background-color: #dc3545; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 10px 0; }
                .motivo-box { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 25px 0; border-radius: 0 5px 5px 0; }
                .motivo-box h3 { color: #856404; font-size: 16px; margin-bottom: 10px; }
                .motivo-box p { font-size: 14px; color: #856404; }
                .cta-button { display: inline-block; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; text-align: center; margin: 20px 0; }
                .cta-button:hover { transform: translateY(-2px); color: white; text-decoration: none; }
                .footer { background-color: #f8f9fa; padding: 30px 20px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { font-size: 14px; color: #6c757d; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>📋 Informações sobre seu cadastro</h1>
                    <p>AMCIG - Associação de Moradores e Comerciantes</p>
                </div>
                
                <div class="content">
                    <div class="welcome-message">
                        Olá, ' . htmlspecialchars($dadosAssociado['nome']) . '!
                    </div>
                    
                    <div class="message">
                        <p>Esperamos que esteja bem. Escrevemos para informar sobre o status do seu cadastro na <strong>Associação de Moradores e Comerciantes da Ilha de Guriri (AMCIG)</strong>.</p>
                        <p>Após análise cuidadosa pela nossa equipe, infelizmente não foi possível aprovar seu cadastro neste momento.</p>
                    </div>
                    
                    <div class="info-box">
                        <h3>📋 Informações do seu cadastro:</h3>
                        <p><strong>Nome:</strong> ' . htmlspecialchars($dadosAssociado['nome']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($dadosAssociado['email']) . '</p>
                        <p><strong>CPF:</strong> ' . htmlspecialchars($dadosAssociado['cpf']) . '</p>
                        <p><strong>Tipo de Associado:</strong> ' . $tipoAssociado . '</p>
                        <p><strong>Status:</strong> <span class="status-badge">REJEITADO</span></p>
                    </div>
                    
                    <div class="motivo-box">
                        <h3>📝 Motivo da rejeição:</h3>
                        <p>' . htmlspecialchars($motivoRejeicao) . '</p>
                    </div>
                    
                    <div class="message">
                        <p><strong>🔄 E agora?</strong></p>
                        <p>Você pode:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Corrigir as informações conforme o motivo indicado</li>
                            <li>Realizar um novo cadastro</li>
                            <li>Entrar em contato conosco para esclarecimentos</li>
                            <li>Aguardar futuras oportunidades de cadastro</li>
                        </ul>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . route('associado.index') . '" class="cta-button">
                            🔄 Realizar Novo Cadastro
                        </a>
                    </div>
                    
                    <div class="message">
                        <p><strong>📞 Precisa de ajuda?</strong></p>
                        <p>Se você tiver dúvidas sobre o motivo da rejeição ou precisar de esclarecimentos, entre em contato conosco através dos canais oficiais da AMCIG.</p>
                    </div>
                </div>
                
                <div class="footer">
                    <p><strong>AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri</strong></p>
                    <p>📍 São Mateus - ES | 📧 contato@amcig.online</p>
                    <p>© ' . date('Y') . ' AMCIG. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
