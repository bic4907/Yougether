<?php

namespace App\Console\Commands;

use App\Room;
use Illuminate\Console\Command;

class RoomManageDaemon extends Command
{
    protected $signature = 'room:manage';
    protected $description = 'Room Management Daemon';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $rooms = Room::all();
        foreach ($rooms as $room) {

            // 만약 방에 비디오가 틀어져 있지 않은 경우
            if ($room->getRoomState() == RoomState::InActive) {

                $nextVideo = $room->getNextQueuedVideo();

                // 만약 재생할 비디오가 있을 경우
                if ($nextVideo != null) {
                    $room->playVideo($nextVideo);
                }

            } else if($room->getRoomState() == RoomState::Active) {

                if($room->isVideoGettingFinished() or $room->isRoomAlive() == false) {

                    $nextVideo = $room->getNextQueuedVideo();

                    if($nextVideo != null) {
                        // 바로 다음 동영상 재생
                        $room->playVideo($nextVideo);
                    } else {
                        // 만약 다음 비디오가 없어 다음 비디오 추가가 필요한 경우
                        $rcVideo = $room->getRecommendedVideo($room->current_videoId);
                        $room->enqueueVideo($rcVideo->videoId, $room->getCurrentHost());
                    }

                }
            }
        }
    }
}
