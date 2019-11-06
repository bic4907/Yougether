<?php

namespace App\Http\Controllers\API;

use App\VideoInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecommendedVideoController extends ParserController
{
    private $VIDEO_INFO_URL = 'https://www.googleapis.com/youtube/v3/search';

    function getNextVideoId(string $videoId) {
        $rawJson = $this->getJSON(
            $this->VIDEO_INFO_URL,
            array(
                'relatedToVideoId'=>$videoId,
                'part'=>'id',
                'type'=>'video',
                'maxResults'=>'1'
            )
        )['items'][0];

        return $rawJson['id']['videoId'];
    }

    function getNextVideoInfo(string $videoId) {
        $nextVideoId = $this->getNextVideoId($videoId);
        return (new VideoInfoParserController)->getVideoInfo($nextVideoId);
    }

}
