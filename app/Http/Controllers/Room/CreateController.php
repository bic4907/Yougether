<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;

class CreateController extends Controller
{
    public function makingRoom(Request $request)
    {
        $todo_room = new Room();
        $todo_room->title = $request->input('roomName');

        $todo_room->save();

        return route('room.enter', [$todo_room->id]);
    }
}
