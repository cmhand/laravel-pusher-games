<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MovedEvent implements ShouldBroadcastNow
{
    use SerializesModels, InteractsWithSockets;

    public $id;
    public $left;
    public $top;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $left, $top)
    {
        $this->id = $id;
        $this->left = $left;
        $this->top = $top;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('game');
    }

    public function broadcastAs()
    {
        return 'moved';
    }

    public function broadcastWith()
    {
        return ['id' => $this->id, 'left' => $this->left, 'top' => $this->top];
    }
}