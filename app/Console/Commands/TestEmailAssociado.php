<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Services\EmailService;

class TestEmailAssociado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-associado {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o envio de email de boas-vindas para associados usando SwiftMailer';

    private $emailService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Criar um usuário fictício para teste
        $user = new User([
            'name' => 'João da Silva',
            'email' => $email,
            'cpf' => '123.456.789-00',
            'tipo_associado' => 'morador',
            'nome_comercio' => null,
        ]);
        
        try {
            $this->info("Testando envio de email usando SwiftMailer...");
            $this->info("Servidor: smtp.mailgun.org:587 (TLS)");
            $this->info("Usuário: associe@amcig.online");
            $this->info("Destinatário: {$email}");
            
            $dadosAssociado = [
                'nome' => $user->name,
                'email' => $user->email,
                'cpf' => $user->cpf,
                'tipo_associado' => $user->tipo_associado,
            ];

            $resultado = $this->emailService->enviarEmailBoasVindas(
                $email,
                $user->name,
                $dadosAssociado
            );

            if ($resultado) {
                    $this->info("✅ Email enviado com sucesso para: {$email}");
                    $this->info("📧 Usando SwiftMailer com Mailgun SMTP");
            } else {
                $this->error("❌ Falha ao enviar email para: {$email}");
            }

        } catch (\Exception $e) {
            $this->error("Erro ao enviar email: " . $e->getMessage());
            $this->error("Detalhes: " . $e->getTraceAsString());
        }
    }
}
