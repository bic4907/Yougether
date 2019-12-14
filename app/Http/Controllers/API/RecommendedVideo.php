<?php

namespace App\Http\Controllers\API;

use App\VideoInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecommendedVideo extends Parser
{
    static private $VIDEO_INFO_URL = 'https://www.googleapis.com/youtube/v3/search';

    static function getNextVideoId(string $videoId) {
        $rawJson = Parser::getJSON(
            self::$VIDEO_INFO_URL,
            array(
                'relatedToVideoId'=>$videoId,
                'part'=>'id',
                'type'=>'video',
                'maxResults'=>'1'
            )
        )['items'][0];

        return $rawJson['id']['videoId'];
    }

    static function getNextVideoInfo(string $videoId) {
        $nextVideoId = self::getNextVideoId($videoId);
        return (new VideoInfoParser)->getVideoInfo($nextVideoId);
    }

}
