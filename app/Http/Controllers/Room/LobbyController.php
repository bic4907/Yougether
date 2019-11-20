<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\User;
use App\Room;
use App\Models\VideoInfo;
use Illuminate\Support\Facades\Auth;

class LobbyController extends Controller
{
    public function show(){
        if(Auth::user()) {
            $this->lobbyChecking();
        }

        $room_info = $this->roomInformation();

        return view('lobby', ['room_info'=>$room_info[0], 'admission'=>$room_info[1]]);
    }

    static public function roomInformation(){

        $tb_video_info = (new Videoinfo())->getTable();
        $tb_room = (new Room())->getTable();
        $admission = Null;

        $room_info[0] = Room::leftJoin($tb_video_info, function ($join) use ($tb_video_info, $tb_room) {
            $join->on($tb_room.'.current_videoId', '=', $tb_video_info.'.videoId');
        })->orderBy($tb_room.'.id', 'asc')->select([$tb_room.'.id', $tb_room.'.title', $tb_video_info.'.videoTitle'])->paginate(6);

        for($i=0;$i<sizeof($room_info[0]);$i++) {
            $admission[$i] = User::where('room_id', $room_info[0][$i]->id)->count();
        }

        $room_info[1] = $admission;

        return $room_info;
    }

    public function lobbyChecking()
    {
        User::where('nickname', Auth::user()->nickname)->update(['room_id' => Null]);
    }
}
