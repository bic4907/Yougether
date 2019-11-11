<?php

namespace App\Http\Controllers\API;

use App\Models\VideoInfo;

class VideoInfoParserController extends ParserController
{
    private $VIDEO_INFO_URL = 'https://www.googleapis.com/youtube/v3/videos';

    /**
     * 비디오 정보를 Youtube Data API를 이용해서 가져온다.
     * 가져온 정보는 DB에 캐싱하게 되고, 다음번에 요청할 시 DB에서 읽어서 반환하게 된다.
     *
     * @param string $videoId Youtube 비디오 재생ID
     * @return VideoInfo 비디오 정보 객체
     */
    function getVideoInfo(string $videoId) {

        // 캐싱된 비디오정보는 바로 반환시킨다.
        $vi = VideoInfo::where('videoId', '=', $videoId)->first();
        if($vi != null) return $vi;

        $rawJson = $this->getJSON(
            $this->VIDEO_INFO_URL,
            array(
                'id'=>$videoId,
                'part'=>'contentDetails, snippet',
            )
        )['items'][0];

        // 새로운 비디오 데이터베이스에 저장
        $vi  = new VideoInfo();
        $vi->videoId = $rawJson['id'];
        $vi->videoTitle = $rawJson['snippet']['title'];
        $vi->videoDesc = $rawJson['snippet']['description'];
        $vi->duration = Util::ISO8601ToSeconds($rawJson['contentDetails']['duration']);
        $vi->tags = $rawJson['snippet']['tags'];
        $vi->thumbnail = $rawJson['snippet']['thumbnails']['medium'];
        $vi->save();

        return $vi;
    }
}
