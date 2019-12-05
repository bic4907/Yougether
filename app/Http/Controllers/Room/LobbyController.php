<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\User;
use App\Room;
use App\Models\VideoInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class LobbyController extends Controller
{
    public function show(){

        if(Auth::user()) {
            $this->lobbyChecking();
        }
        $tb_video_info = (new Videoinfo())->getTable();
        $tb_room = (new Room())->getTable();

        $room_info = Room::leftJoin($tb_video_info, function ($join) use ($tb_video_info, $tb_room) {
            $join->on($tb_room.'.current_videoId', '=', $tb_video_info.'.videoId');
            })->orderBy($tb_room.'.id', 'asc')->select([$tb_room.'.id'])->paginate(6);

        for($i=0;$i<sizeof($room_info);$i++){
            $temp[$i] = $room_info[$i]->id;
        }

        $room_id_array = implode( ',', $temp );

        return view('lobby', ['room_info'=>$room_id_array]);
    }

    static public function roomInformation(Request $request)
    {

        $tb_video_info = (new Videoinfo())->getTable();
        $tb_room = (new Room())->getTable();
        $admission = Null;

        $room = Room::leftJoin($tb_video_info, function ($join) use ($tb_video_info, $tb_room) {
            $join->on($tb_room.'.current_videoId', '=', $tb_video_info.'.videoId');
        })->where($tb_room.'.id', '=', $request->get('room_id'))->first();

        $room->admission = Redis::get($request->get('room_id'));
        $room->room_id = $request->get('room_id');

        return $room;
    }

    public function lobbyChecking()
    {
        $room_id = User::where('nickname', Auth::user()->nickname)->get('room_id');
        Redis::set($room_id, Redis::get($room_id) - 1);
    }
}
