<?php

namespace App\Listeners;

use App\Events\SolicitacaoCriada;
use App\Mail\SolicitacaoCriadaMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarEmailSolicitacaoCriada
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SolicitacaoCriada  $event
     * @return void
     */
    public function handle(SolicitacaoCriada $event)
    {
        try {
            $solicitacao = $event->solicitacao;
            
            // Enviar email para o solicitante
            Mail::to($solicitacao->user->email)
                ->send(new SolicitacaoCriadaMail($solicitacao));
            
            Log::info('Email de solicitação criada enviado para: ' . $solicitacao->user->email, [
                'solicitacao_id' => $solicitacao->id,
                'user_id' => $solicitacao->user->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de solicitação criada: ' . $e->getMessage(), [
                'solicitacao_id' => $event->solicitacao->id,
                'user_id' => $event->solicitacao->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
