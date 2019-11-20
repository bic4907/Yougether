<?php

namespace App\Console\Commands;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Events\VideoSyncEvent;
use App\Http\Controllers\API\RecommendedVideoController;
use App\Room;
use App\Video;
use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use App\Models\VideoInfo;
use App\Events\RoomInfoChangeEvent;

class RoomManageDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'room:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Room Management Daemon';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rooms = Room::all();
        foreach ($rooms as $room) {
            // 만약 방에 비디오가 틀어져 있지 않은 경우
            if ($room->current_videoId == null) {
                $nextVideo = Video::where('room_id', '=', $room->id)
                    ->where('status', '=', VideoStatus::Queued)
                    ->first();
                // 만약 재생할 비디오가 있을 경우
                if ($nextVideo != null) {

                    // 방 정보를 해당 비디오 값으로 변경한다.
                    $room->current_videoId = $nextVideo->video;

                    // 비디오 재생정보 변경
                    $room->current_videoStatus = VideoStatus::Playing;
                    $nextVideo->status = VideoStatus::Playing;

                    $room->current_time = 0;
                    $room->current_duration = $nextVideo->info()->duration;

                    $room->save();
                    $nextVideo->save();

                    // 바뀐 재생정보 Broadcast
                    broadcast(new VideoSyncEvent(
                        $room->id, $room->current_videoId, $room->current_videoStatus, $room->current_time
                    ));

                    // 바뀐 방정보 Broadcast
                    broadcast(new VideoAddEvent($room->id));
                    broadcast(new RoomInfoChangeEvent($room->id));
                }
            } else {
                // 만약 플레이중인 비디오가 있을 경우
                // 재생시간이 다 와가는지 검사
                // 재생하고 있는 유저가 존재하는지 확인
                // 유효하지 않을 경우 다음차례에게 넘겨줌

                if(($room->current_duration - $room->current_time <= 3) or
                    User::where('id', '=', $room->current_host)->first() == null or
                    (Carbon::now()->timestamp - $room->updated_at->timestamp >= 5)
                ) {
                    // 방장을 바꿔주어야함

                    // 재생중이던 비디오는 재생완료로 바꿈
                    Video::where('video', '=', $room->current_videoId)
                        ->where('status', '=', VideoStatus::Playing)
                        ->update(['status'=>VideoStatus::Played]);

                    $nextVideo = Video::where('room_id', '=', $room->id)
                        ->where('status', '=', VideoStatus::Queued)
                        ->first();
                    if($nextVideo != null) {
                        if($nextVideo->info() == null) { continue; }
                        $room->current_host = $nextVideo->user_id;
                        $room->current_videoId = $nextVideo->video;

                        // 비디오 재생정보 변경
                        $room->current_videoStatus = VideoStatus::Playing;
                        $nextVideo->status = VideoStatus::Playing;

                        $room->current_time = 0;

                        $room->current_duration = $nextVideo->info()->duration;

                        $room->save();
                        $nextVideo->save();

                        // 바뀐 재생정보 Broadcast
                        broadcast(new VideoSyncEvent(
                            $room->id, $room->current_videoId, $room->current_videoStatus, $room->current_time
                        ));

                        // 바뀐 방정보 Broadcast
                        broadcast(new VideoAddEvent($room->id));
                        broadcast(new RoomInfoChangeEvent($room->id));

                    } else {
                        // 추천비디오
                        $rcVideo = null;
                        try {
                            $rcVideo = RecommendedVideoController::getNextVideoInfo($room->current_videoId);
                        } catch(\ErrorException $exception) {
                            $rcVideo = VideoInfo::all()->random(1)->first();
                        }

                        if($rcVideo == null) continue;
                        $nextVideo = new Video();
                        $nextVideo->user_id = $room->current_host;
                        $nextVideo->video = $rcVideo->videoId;
                        $nextVideo->status = VideoStatus::Queued;
                        $nextVideo->room_id = $room->id;
                        $nextVideo->save();

                        broadcast(new VideoAddEvent($room->id));
                    }

                }
            }
        }
    }
}
