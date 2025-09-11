<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Solicitacao;

class SolicitacaoCriadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitacao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Solicitacao $solicitacao)
    {
        $this->solicitacao = $solicitacao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Solicitação Recebida - AMCIG #' . $this->solicitacao->id)
                    ->from('contato@amcig.online', 'AMCIG')
                    ->view('emails.solicitacao-criada')
                    ->with([
                        'solicitacao' => $this->solicitacao
                    ]);
    }
}
