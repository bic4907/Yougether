<?php

namespace App\Http\Controllers\Room;

use Illuminate\Http\Request;
use App\Room;
use App\Models\VideoInfo;

class LobbyController extends Controller
{
    public function show(){

        $room_title = Room::get('title');
//        $video_title = VideoInfo::where()

        return view('lobby', ['room_title'=>$room_title]);
    }
}
