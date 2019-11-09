<?php

namespace App\Http\Controllers\Video;

use App\Events\VideoAddEvent;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends Controller
{
    public function addVideo(Request $request)
    {
        $video = new Video();
        $video->user_id = $request->user_id;
        $video->video = $request->video;
        $video->isPlayed = false;
        $video->room_id = $request->room_id;
        $video->save();

        broadcast(new VideoAddEvent());
    }
}
