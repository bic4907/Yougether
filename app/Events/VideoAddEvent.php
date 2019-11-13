<?php

namespace App\Events;

use App\Enums\VideoStatus;
use App\Http\Controllers\API\VideoInfoParserController;
use App\Video;
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
    public $video_info;
    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $videoId, $room_id)
    {
        $this->user_id = $user_id;
        $this->video_info = VideoInfoParserController::getVideoInfo($videoId);
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

    public function broadcastwith()
    {
        $videoList = Video::where('room_id', $this->room_id) //해당 방에 있는
        ->whereIn('status', [VideoStatus::Queued, VideoStatus::Playing]) //재생 중이거나 리스트에 있는 비디오만
        ->get();
        return json_encode($videoList);
    }
}
