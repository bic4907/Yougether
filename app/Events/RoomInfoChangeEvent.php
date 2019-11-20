<?php

namespace App\Events;

use App\Enums\VideoStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Room;
use App\User;

class RoomInfoChangeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id)
    {
        $this->room_id = $room_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.'.$this->room_id);
    }

    public function broadcastWith()
    {
        $room_info = Room::find($this->room_id);
        $room_info->host_nickname = User::find($room_info->current_host)->nickname;
        return ['roomInfo'=>$room_info];
    }
}
