<?php

namespace App;

use App\Enums\RoomState;
use App\Enums\VideoStatus;
use App\Events\RoomInfoChangeEvent;
use App\Events\VideoAddEvent;
use App\Events\VideoSyncEvent;
use App\Http\Controllers\API\RecommendedVideo;
use App\Models\VideoInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'title', 'current_host', 'current_videoId', 'current_time',
    ];

    public function videos()
    {
        return $this->hasMany('App/Video');
    }

    public function users()
    {
        return $this->hasMany('App/User');
    }

    public function chats()
    {
        return $this->hasMany('App/Chat');
    }

    /**
     * @return Video 재생중인 비디오 객체를 반환한다.
     */
    function getCurrentVideo() {
        $curVideo = Video::where('video', '=', $this->current_videoId)
            ->where('status', '=', VideoStatus::Playing)->first();
        return $curVideo;
    }

    /**
     * 현재 방에서 방장을 가지고 있는 유저의 객체를 반환합니다.
     *
     * @return User 방장의 유저 객체
     */
    function getCurrentHost() {
        $curHost = User::where('id', '=', $this->current_host)->first();
        return $curHost;
    }


    /**
     * 방의 플레이리스트에 추가되어있는 비디오 중 다음비디오를 가져온다.
     *
     * @return Video 다음 비디오 객체 (Nullable)
     */
    function getNextQueuedVideo() {
        $nextVideo = Video::where('room_id', '=', $this->id)
            ->where('status', '=', VideoStatus::Queued)
            ->first();

        return $nextVideo;
    }

    /**
     * 만약 추천비디오API 할당량이 있을 경우 그것을 리턴하고,
     * 만약 할당량이 없다면, 캐싱 되어있는 비디오 중 하나를 리턴함.
     *
     * @param Video $video 추천 기준이 될 비디오정보 객체
     * @return VideoInfo|null 비디오 정보 객체
     */
    function getRecommendedVideo(string $videoId) {
        $rcVideo = null;
        try {
            $rcVideo = RecommendedVideo::getNextVideoInfo($videoId);
        } catch(\ErrorException $exception) {
            // 비디오 할당량이 없다면 오류 발생
            $rcVideo = VideoInfo::all()->random(1)->first();
        }
        return $rcVideo;
    }

    /**
     * @return bool 방의 비디오가 끝나가는지 여부 반환
     */
    function isVideoGettingFinished() {
        return ($this->current_duration - $this->current_time <= 3);
    }

    /**
     * @return bool 방장이 살아있으면서 동기화를 제대로 하고 있는지 반환함
     */
    function isRoomAlive() {
        $isAlive = true;

        // 방장이 동기화를 해주고 있는지 확인
        if(Carbon::now()->timestamp - $this->updated_at->timestamp >= 5) {
            $isAlive = false;
        }

        // 방장이 접속이 종료된 경우
        if(User::where('id', '=', $this->current_host)->first() == null) {
            $isAlive = false;
        }

        return $isAlive;
    }

    /**
     * 재생중인 동영상 정보를 완료로 변경하고,
     * 다음 동영상을 재생하며, 브로드캐스팅 한다.
     *
     * @param Video $video 재생할 다음 동영상 정보
     */
    function playVideo(Video $video) {

        // 재생중인 비디오 정보는 완료로 표시함
        $curVideo = $this->getCurrentVideo();
        if($curVideo != null) {
            $curVideo->status == VideoStatus::Played;
            $curVideo->save();
        }

        $this->current_host = $video->user_id;
        $this->current_videoId = $video->video;

        // 비디오 재생정보 변경
        $this->current_videoStatus = VideoStatus::Playing;
        $video->status = VideoStatus::Playing;

        $this->current_time = 0;
        $this->current_duration = $video->info()->duration;

        $this->save();
        $video->save();


        broadcast(new VideoSyncEvent(
            $this->id,
            $this->current_videoId,
            $this->current_videoStatus,
            $this->current_time
        ));
        broadcast(new VideoAddEvent($this->id));
        broadcast(new RoomInfoChangeEvent($this->id));
    }

    /**
     * @return RoomState 방이 처음만들어진 상태인지, 한번 재생기록이 있는 방인지 반환
     */
    function getRoomState() {
        if($this->current_videoId == null and $this->current_time == null) {
            return RoomState::InActive;
        } else {
            return RoomState::Active;
        }
    }

    /**
     * 방에 플레이리스트를 추가한다.
     *
     * @param string $videoId 추가할 유튜브 고유 비디오ID 아이디
     * @param User $owner 추가할 비디오
     */
    function enqueueVideo(string $videoId, User $owner) {
        $nextVideo = new Video();
        $nextVideo->user_id = $owner->id;
        $nextVideo->video = $videoId;
        $nextVideo->status = VideoStatus::Queued;
        $nextVideo->room_id = $this->id;
        $nextVideo->save();

        broadcast(new VideoAddEvent($this->id));
    }



}
