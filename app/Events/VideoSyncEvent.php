<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoSyncEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;
    public $videoId;
    public $videoStatus;
    public $videoTime;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $videoId, $videoStatus, $videoTime)
    {
        $this->room_id = $room_id;
        $this->videoId = $videoId;
        $this->videoStatus = $videoStatus;
        $this->videoTime = $videoTime;
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
        return ['videoId' => $this->videoId, 'videoStatus'=>$this->videoStatus, 'videoTime'=>$this->videoTime];
    }

}
