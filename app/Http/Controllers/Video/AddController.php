<?php

namespace App\Http\Controllers\Video;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Http\Controllers\API\VideoInfoParserController;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends Controller
{
    public function addVideo($room_id, $videoId, Request $request)
    {
        $video = new Video();
        $video->user_id = $request->user_id;
        $video->video = $request->video;
        $video->room_id = $room_id;
        $video->save();

        $video_info = VideoInfoParserController::getVideoInfo($videoId);

        broadcast(new VideoAddEvent($video->user_id, $video_info, $room_id));
    }
}
