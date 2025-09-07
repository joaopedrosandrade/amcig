<?php

namespace App\Listeners;

use App\Events\AssociadoCadastrado;
use App\Services\EmailService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnviarEmailAssociadoCadastrado
{
    private $emailService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handle the event.
     *
     * @param  AssociadoCadastrado  $event
     * @return void
     */
    public function handle(AssociadoCadastrado $event)
    {
        try {
            Log::info('Iniciando envio de email para: ' . $event->user->email);
            
            $dadosAssociado = [
                'nome' => $event->user->name,
                'email' => $event->user->email,
                'cpf' => $event->user->cpf,
                'tipo_associado' => $event->user->tipo_associado,
            ];

            $resultado = $this->emailService->enviarEmailBoasVindas(
                $event->user->email,
                $event->user->name,
                $dadosAssociado
            );

            if ($resultado) {
                Log::info('Email de boas-vindas enviado com sucesso para: ' . $event->user->email);
            } else {
                Log::error('Falha ao enviar email de boas-vindas para: ' . $event->user->email);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
            Log::error('Detalhes do erro: ' . $e->getTraceAsString());
        }
    }
}
