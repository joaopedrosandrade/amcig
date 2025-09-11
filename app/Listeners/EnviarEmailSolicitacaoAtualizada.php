<?php

namespace App\Listeners;

use App\Events\SolicitacaoAtualizada;
use App\Mail\SolicitacaoAtualizadaMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarEmailSolicitacaoAtualizada
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
     * @param  \App\Events\SolicitacaoAtualizada  $event
     * @return void
     */
    public function handle(SolicitacaoAtualizada $event)
    {
        try {
            $solicitacao = $event->solicitacao;
            $statusAnterior = $event->statusAnterior;
            
            // Só enviar email se o status mudou para algo relevante
            $statusRelevantes = ['EM_ANALISE', 'EM_ANDAMENTO', 'CONCLUIDA', 'CANCELADA', 'REJEITADA'];
            
            if (in_array($solicitacao->status, $statusRelevantes)) {
                // Enviar email para o solicitante
                Mail::to($solicitacao->user->email)
                    ->send(new SolicitacaoAtualizadaMail($solicitacao, $statusAnterior));
                
                Log::info('Email de solicitação atualizada enviado para: ' . $solicitacao->user->email, [
                    'solicitacao_id' => $solicitacao->id,
                    'user_id' => $solicitacao->user->id,
                    'status_anterior' => $statusAnterior,
                    'status_atual' => $solicitacao->status
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de solicitação atualizada: ' . $e->getMessage(), [
                'solicitacao_id' => $event->solicitacao->id,
                'user_id' => $event->solicitacao->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
