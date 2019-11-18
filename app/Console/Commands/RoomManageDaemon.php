<?php

namespace App\Console\Commands;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Events\VideoSyncEvent;
use App\Room;
use App\Video;
use Illuminate\Console\Command;

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
        for($i = 0; $i < 30; $i++) {
            $rooms = Room::all();
            print($rooms);
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
                    }
                }
            }
            sleep(1);
        }
    }
}
