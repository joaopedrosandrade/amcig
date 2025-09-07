<?php

namespace App\Listeners;

use App\Events\AssociadoAprovado;
use App\Services\EmailService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnviarEmailAssociadoAprovado
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
     * @param  AssociadoAprovado  $event
     * @return void
     */
    public function handle(AssociadoAprovado $event)
    {
        try {
            Log::info('Iniciando envio de email de aprovação para: ' . $event->user->email);
            
            $dadosAssociado = [
                'nome' => $event->user->name,
                'email' => $event->user->email,
                'cpf' => $event->user->cpf,
                'matricula' => $event->user->matricula,
                'tipo_associado' => $event->user->tipo_associado,
                'data_aprovacao' => $event->user->data_aprovacao,
            ];

            $resultado = $this->emailService->enviarEmailAprovacao(
                $event->user->email,
                $event->user->name,
                $dadosAssociado
            );

            if ($resultado) {
                Log::info('Email de aprovação enviado com sucesso para: ' . $event->user->email);
            } else {
                Log::error('Falha ao enviar email de aprovação para: ' . $event->user->email);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de aprovação: ' . $e->getMessage());
            Log::error('Detalhes do erro: ' . $e->getTraceAsString());
        }
    }
}
