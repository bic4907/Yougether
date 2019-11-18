<?php

namespace Tests\Feature;

use App\Enums\VideoStatus;
use App\Events\VideoAddEvent;
use App\Events\VideoSyncEvent;
use App\Http\Controllers\API\RecommendedVideoController;
use App\Http\Controllers\API\VideoInfoParserController;
use App\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Room;

class RoomManageTest extends TestCase
{
    /**
     * 방에 아무동영상이 틀어져있지 않은 경우 동영상이 들어온 것을 감지해서 틀어준다.
     */
    public function testRoomNoVideo()
    {
        $rooms = Room::all();
        print($rooms);
        foreach($rooms as $room) {
            // 만약 방에 비디오가 틀어져 있지 않은 경우
            if($room->currentVideoStatus == null) {
                $nextVideo = Video::where('room_id', '=', $room->id)
                    ->where('status', '=', VideoStatus::Queued)
                    ->first();
                // 만약 재생할 비디오가 있을 경우
                if($nextVideo != null) {

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

        $this->assertTrue(true);
    }

}
