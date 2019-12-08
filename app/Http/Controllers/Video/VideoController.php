<?php

namespace App\Http\Controllers\Video;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Http\Controllers\API\VideoInfoParserController;
use App\Http\Controllers\UserLog\UserLogController;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function addVideo($room_id, Request $request)
    {
        if (!User::checkUserAddCount(Auth::user()->id, $room_id))
        {
            abort(503);
        }

        $video = new Video();
        $video->user_id = Auth::user()->id;
        $video->video = $request->video_id;
        $video->room_id = $room_id;
        $video->save();
        //비디오 추가 시 유저의 비디오 추가 로그 생성
        UserLogController::addUserAddVideoCount(Auth::user()->id, $room_id);

        // 비디오 정보 캐싱
        VideoInfoParserController::getVideoInfo($video->video);

        broadcast(new VideoAddEvent($room_id));
    }

    public function getVideoList($room_id)
    {
        $videoList = DB::table('videos')
            ->join('video_infos', 'videos.video', '=', 'video_infos.videoId')
            ->where('videos.room_id','=', $room_id)
            ->whereIn('status', [VideoStatus::Queued]) //리스트에 있는 비디오만
            ->get();
        return $videoList;
    } //해당 방의 Video List return
}
