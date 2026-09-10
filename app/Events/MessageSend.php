<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // TODO: Corregir migraciones para crear una sala 
            new PrivateChannel('chat.' . $this->message->user_id),     // Canal del que envía Usuario A
            new PrivateChannel('chat.' . $this->message->receiver_id), // Canal del que recibe Usuario B
        ];
    }

    
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}