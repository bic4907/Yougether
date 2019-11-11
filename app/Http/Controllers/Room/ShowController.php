<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;

class ShowController extends Controller
{
    public function show($room_id, Request $request)
    {
        $room = Room::findOrFail($room_id);

        return view('room', ['room'=>$room]);
    }
}
