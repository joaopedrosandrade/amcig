<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Solicitacao;

class SolicitacaoAtualizada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $solicitacao;
    public $statusAnterior;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Solicitacao $solicitacao, $statusAnterior = null)
    {
        $this->solicitacao = $solicitacao;
        $this->statusAnterior = $statusAnterior;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
