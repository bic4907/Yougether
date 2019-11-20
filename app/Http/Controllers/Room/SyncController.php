<?php

namespace App\Http\Controllers\Room;

use App\Enums\VideoStatus;
use App\Events\MessageSentEvent;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;
use App\Room;
use App\Events\VideoSyncEvent;
use Illuminate\Support\Facades\Auth;

class SyncController extends Controller
{

    public function renew($room_id, Request $request) {

        if($request->input('videoId') == null or
            $request->input('videoTime') == null
        ) abort(503);


        // 2. 만약 요청한 유저가 호스트가 맞다면 방 정보를 갱신합니다.
        $room = Room::findOrFail($room_id);

        if(Auth::user() and $room->current_host != Auth::user()->id) {
            abort(503);
        }

        $room->current_videoId = $request->input('videoId');
        $room->current_time = $request->input('videoTime');
        $room->current_videoStatus = VideoStatus::Playing;
        $room->save();

        // 3. 방 전체 유저들에게 BroadCasting 합니다 (방장제외)
        broadcast(new VideoSyncEvent($room_id, $room->current_videoId, VideoStatus::Playing, $room->current_time));

        return 'OK';
    }
}
