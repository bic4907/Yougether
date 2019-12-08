<?php

namespace App\Http\Controllers\Room;

use App\Enums\VideoStatus;
use App\Events\MessageSentEvent;
use App\Http\Controllers\Controller;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use App\Room;
use App\Events\VideoSyncEvent;
use Illuminate\Support\Facades\Auth;

class RoomSyncController extends Controller
{

    public function updateRoomSync($room_id, Request $request) {
        // 2. 만약 요청한 유저가 호스트가 맞다면 방 정보를 갱신합니다.
        $this->updateRoomSyncException($room_id, $request);

        $room = Room::findOrFail($room_id);

        $room->current_videoId = $request->input('videoId');
        $room->current_time = $request->input('videoTime');
        $room->current_videoStatus = VideoStatus::Playing;
        $room->save();

        // 3. 방 전체 유저들에게 BroadCasting 합니다 (방장제외)
        broadcast(new VideoSyncEvent($room_id, $room->current_videoId, VideoStatus::Playing, $room->current_time));

        return 'OK';
    }

    public function updateRoomSyncException($room_id, Request $request)
    {
        if($request->input('videoId') == null or
            $request->input('videoTime') == null
        ) abort(503);

        $user = Auth::user();
        $room = Room::findOrFail($room_id);

        if(($user != null) and $room->current_host != $user->id) {
            abort(503);
        }

        //유저가 해당 방에서 비디오를 얼마나 되감기했는지 검사
        if(!User::checkUserUpdateCount($user->id, $room_id))
        {
            abort(503);
        }
    }
}
