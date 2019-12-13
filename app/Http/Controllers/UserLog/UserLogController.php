<?php

namespace App\Http\Controllers\UserLog;

use App\Http\Controllers\Controller;
use App\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserLogController extends Controller
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

    public static function addUserUpdateVideoCount($user_id, $room_id)
    {
        $user_log = new UserLog();
        $user_log->user_id = $user_id;
        $user_log->room_id = $room_id;
        $user_log->add_video = false;
        $user_log->rewind = true;
        $user_log->save();
    }
}
