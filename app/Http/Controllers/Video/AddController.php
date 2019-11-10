<?php

namespace App\Http\Controllers\Video;

use App\Events\VideoAddEvent;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends Controller
{
    public function addVideo($room_id, Request $request)
    {
        $video = new Video();
        $video->user_id = $request->user_id;
        $video->video = $request->video;
        $video->isPlayed = false;
        $video->room_id = $room_id;
        $video->save();

        broadcast(new VideoAddEvent($video->user_id, $video->video, $room_id));
    }
}
