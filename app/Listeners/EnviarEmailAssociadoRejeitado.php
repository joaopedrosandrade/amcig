<?php

namespace App\Listeners;

use App\Events\AssociadoRejeitado;
use App\Services\EmailService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnviarEmailAssociadoRejeitado
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
     * @param  AssociadoRejeitado  $event
     * @return void
     */
    public function handle(AssociadoRejeitado $event)
    {
        try {
            Log::info('Iniciando envio de email de rejeição para: ' . $event->user->email);
            
            $dadosAssociado = [
                'nome' => $event->user->name,
                'email' => $event->user->email,
                'cpf' => $event->user->cpf,
                'tipo_associado' => $event->user->tipo_associado,
                'motivo_rejeicao' => $event->motivo,
            ];

            $resultado = $this->emailService->enviarEmailRejeicao(
                $event->user->email,
                $event->user->name,
                $dadosAssociado
            );

            if ($resultado) {
                Log::info('Email de rejeição enviado com sucesso para: ' . $event->user->email);
            } else {
                Log::error('Falha ao enviar email de rejeição para: ' . $event->user->email);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de rejeição: ' . $e->getMessage());
            Log::error('Detalhes do erro: ' . $e->getTraceAsString());
        }
    }
}
