<?php

namespace App\Http\Controllers\API;

use App\VideoInfo;

class VideoInfoParserController extends ParserController
{
    private $VIDEO_INFO_URL = 'https://www.googleapis.com/youtube/v3/videos';

    function getVideoInfo(string $videoId) {
        $rawJson = $this->getJSON(
            $this->VIDEO_INFO_URL,
            array(
                'id'=>$videoId,
                'part'=>'contentDetails, snippet',
            )
        )['items'][0];

        $vi  = new VideoInfo();
        $vi->videoId = $rawJson['id'];
        $vi->title = $rawJson['snippet']['title'];
        $vi->desc = $rawJson['snippet']['description'];
        $vi->duration = Util::ISO8601ToSeconds($rawJson['contentDetails']['duration']);
        $vi->tags = $rawJson['snippet']['tags'];

        return $vi;
    }
}
