<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Curl\Curl;

class ParserController extends Controller
{
    static $YOUTUBE_API_KEY = "AIzaSyCwFrzlv37L5efs-MHHdUxT-S9fAQCVfAQ";

    static function getJSON(string $url, array $params) {

        // Youtube API Key 추가
        $params['key'] = self::$YOUTUBE_API_KEY;

        $curl = new Curl();
        $curl->get($url, $params);
        $curl->close();

        return json_decode($curl->response, true);
    }

}
