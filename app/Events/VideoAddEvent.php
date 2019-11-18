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
use Illuminate\Support\Facades\DB;

class VideoAddEvent implements ShouldBroadcast
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
        $videoList = DB::table('videos')
            ->join('video_infos', 'videos.video', '=', 'video_infos.videoId')
            ->where('videos.room_id','=', $this->room_id)
            ->whereIn('status', [VideoStatus::Queued, VideoStatus::Playing]) //재생 중이거나 리스트에 있는 비디오만
            ->get();
        return ['videoList'=>$videoList];
    }
}
