<?php

namespace App\Http\Controllers\Room;

use App\Enums\VideoStatus;
use App\Events\MessageSentEvent;
use App\Http\Controllers\Controller;
use App\User;
use App\UserLog;
use App\Video;
use Illuminate\Http\Request;
use App\Room;
use App\Events\VideoSyncEvent;
use Illuminate\Support\Facades\Auth;

class RoomSyncController extends Controller
{

    public function updateRoomSync($room_id, Request $request) {
        // 2. 만약 요청한 유저가 호스트가 맞다면 방 정보를 갱신합니다.

        $room = Room::findOrFail($room_id);

        $room->current_videoId = $request->input('videoId');
        $room->current_time = $request->input('videoTime');
        $room->current_videoStatus = VideoStatus::Playing;

        $this->updateRoomSyncException($room); // 예외처리

        $room->save();

        // 3. 방 전체 유저들에게 BroadCasting 합니다 (방장제외)
        broadcast(new VideoSyncEvent($room_id, $room->current_videoId, VideoStatus::Playing, $room->current_time));

        return 'OK';
    } //updateRoomSync()

    public function updateRoomSyncException($room)
    {
        if($room->current_videoId == null or
            $room->current_time == null
        ) abort(503);

        $user = Auth::user();

        if(($user != null) and $room->current_host != $user->id) {
            abort(503);
        }

        //유저가 방장일 때 해당 방에서 비디오를 얼마나 되감기했는지 검사
        if($room->current_host == $user->id)
        {
            if(UserLog::getUserUpdateCount($user->id, $room->id) > 2)
            {
                abort(503);
            }
        }
    } //updateRoomSyncException()
}
