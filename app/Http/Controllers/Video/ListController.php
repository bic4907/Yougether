<?php

namespace App\Http\Controllers\Video;

use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function getList($room_id)
    {
        $videoList = Video::where('room_id', $room_id) //해당 방에 있는
            ->whereIn('status', [VideoStatus::Queued, VideoStatus::Playing]) //재생 중이거나 리스트에 있는 비디오만
            ->get();
        return $videoList;
    } //해당 방의 Video List return
}
