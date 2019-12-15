<?php

namespace App\Http\Controllers\UserLog;

use App\Enums\VideoStatus;
use App\Events\VideoSyncEvent;
use App\Http\Controllers\Controller;
use App\Room;
use App\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddUserLog extends Controller
{
    public static function addUserAddVideoCount($user_id, $room_id)
    {
        $user_log = new UserLog();
        $user_log->user_id = $user_id;
        $user_log->room_id = $room_id;
        $user_log->add_video = true;
        $user_log->rewind = false;
        $user_log->save();
    }

    public function addUserUpdateVideoCount(Request $request, $room_id)
    {
        if($this->getUserUpdateCount($request, $room_id) > 2)
        {
            abort(503);
        } //유저의 동영상 rewind 횟수가 2회 이상이면 제한
                $user_id = $request->user()->id;
                $user_log = new UserLog();
                $user_log->user_id = $user_id;
                $user_log->room_id = $room_id;
                $user_log->add_video = false;
                $user_log->rewind = true;
                $user_log->save();

    }
    public function getUserUpdateCount(Request $request, $room_id)
    {
        $user_id = $request->user()->id;
        $videoUpdateCount = DB::table('user_logs')
            ->where('user_logs.user_id', '=', $user_id)
            ->where('user_logs.room_id', '=', $room_id)
            ->where('rewind', '=', true)
            ->get()
            ->count();

        if($videoUpdateCount == null) return 0;
        return $videoUpdateCount;
    }
}
