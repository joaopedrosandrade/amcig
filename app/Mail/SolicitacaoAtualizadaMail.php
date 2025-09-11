<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Solicitacao;

class SolicitacaoAtualizadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitacao;
    public $statusAnterior;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Solicitacao $solicitacao, $statusAnterior = null)
    {
        $this->solicitacao = $solicitacao;
        $this->statusAnterior = $statusAnterior;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Atualização da Solicitação - AMCIG #' . $this->solicitacao->id)
                    ->from('contato@amcig.online', 'AMCIG')
                    ->view('emails.solicitacao-atualizada')
                    ->with([
                        'solicitacao' => $this->solicitacao,
                        'statusAnterior' => $this->statusAnterior
                    ]);
    }
}
