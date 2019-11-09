<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;
    public $nickname;
    public $text;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $nickname, $text)
    {
        //
        $this->room_id = $room_id;
        $this->nickname = $nickname;
        $this->text = $text;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel(
            'chat'.$this->room_id
        );
    }

    public function broadcastWith()
    {
        return ['nickname' => $this->nickname, 'text'=>$this->text];
    }
}
