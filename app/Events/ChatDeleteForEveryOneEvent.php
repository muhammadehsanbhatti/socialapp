<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatDeleteForEveryOneEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $chat;
    public $receiver_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($chat, $receiver_id)
    {
        $this->chat = $chat;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['chat-delete-for-everyone-channel'];
    }
    
    public function broadcastAs()
    {
        return 'chat-delete-for-everyone-event-'.$this->receiver_id;
    }
}