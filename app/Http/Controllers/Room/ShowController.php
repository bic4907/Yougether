<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use Illuminate\Support\Facades\Auth;
use App\User;

class ShowController extends Controller
{
    public function show($room_id, Request $request)
    {
        $room = Room::findOrFail($room_id);

        $isHost = false;
        if(Auth::user()) {
            $isHost = $room->current_host == Auth::user()->id;
            $this->enterChecking($room_id);
        }

        return view('room', ['room'=>$room, 'isHost'=>$isHost]);
    }

    public function enterChecking($room_id){
        User::where('nickname', Auth::user()->nickname)->update(['room_id'=>$room_id]);
    }
}
