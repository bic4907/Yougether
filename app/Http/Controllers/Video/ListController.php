<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function getList($room_id)
    {
        $videoList = Video::where('room_id', $room_id)
            ->where('isPlayed', false)
            ->get();
        return $videoList;
    } //해당 방의 Video List return
}
