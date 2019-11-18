<?php

namespace App\Http\Controllers\Video;

use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function getList($room_id)
    {
        $videoList = DB::table('videos')
            ->join('video_infos', 'videos.id', '=', 'video_infos.id')
            ->where('videos.room_id','=', $room_id)
            ->whereIn('videoStatus', [VideoStatus::Queued, VideoStatus::Playing]) //재생 중이거나 리스트에 있는 비디오만
            ->get();
        return $videoList;
    } //해당 방의 Video List return
}
