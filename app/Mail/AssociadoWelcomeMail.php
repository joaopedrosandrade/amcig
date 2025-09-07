<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class AssociadoWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bem-vindo à AMCIG - Cadastro Realizado com Sucesso!')
                    ->from(config('mail.from.address', 'noreply@sendgrid.com'), config('mail.from.name', 'AMCIG'))
                    ->view('emails.associado-welcome')
                    ->with([
                        'user' => $this->user
                    ]);
    }
}
