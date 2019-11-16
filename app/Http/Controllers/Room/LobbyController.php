<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Room;
use App\Models\VideoInfo;
use App\Video;


class LobbyController extends Controller
{
    public function show(){

        $tb_video_info = (new Videoinfo())->getTable();
        $tb_room = (new Room())->getTable();

        $room_info = Room::leftJoin($tb_video_info, function ($join) use ($tb_video_info, $tb_room) {
                $join->on($tb_room.'.current_videoId', '=', $tb_video_info.'.videoId');
            })->orderBy($tb_room.'.id', 'asc')->get([$tb_room.'.id', $tb_room.'.title', $tb_video_info.'.videoTitle']);

        for($i=0;$i<sizeof($room_info);$i++) {
            $admission[$i] = User::where('room_id', $room_info[$i]->id)->count();
        }

        return view('lobby', ['room_info'=>$room_info, 'admission'=>$admission]);
    }
}
