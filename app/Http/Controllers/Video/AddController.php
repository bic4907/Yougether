<?php

namespace App\Http\Controllers\Video;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Http\Controllers\API\VideoInfoParserController;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddController extends Controller
{
    public function addVideo($room_id, Request $request)
    {
        $video = new Video();
        $video->user_id = Auth::user()->id;
        $video->video = $request->video_id;
        $video->room_id = $room_id;
        $video->save();

        // 비디오 정보 캐싱
        VideoInfoParserController::getVideoInfo($video->video);
        
        broadcast(new VideoAddEvent($room_id));
    }
}
