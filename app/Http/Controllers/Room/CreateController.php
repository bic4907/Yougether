<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use Auth;

class CreateController extends Controller
{
    public function makingRoom(Request $request)
    {
        $todo_room = new Room();
        $todo_room->title = $request->input('roomName');
        $todo_room->current_host = Auth::user()->nickname; // 방을 만든 사람을 호스트로 지정함
        $todo_room->save();

        return route('room.enter', [$todo_room->id]);
    }
}
