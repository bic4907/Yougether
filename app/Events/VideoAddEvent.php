<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VideoAddEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $video;
    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $video, $room_id)
    {
        $this->$user_id = $user_id;
        $this->$video = $video;
        $this->$room_id = $room_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('video'.$this->room_id);
    }

    public function broadcastwith()
    {
        return ['user_id'=>$this->user_id, 'video'=>$this->video];
    }
}
