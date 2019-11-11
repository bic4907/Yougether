<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Curl\Curl;

class ParserController extends Controller
{
    private $YOUTUBE_API_KEY = "AIzaSyD44WIZGva9o34xBYNrpXVHr0I4rFqggWo";

    function getJSON(string $url, array $params) {

        // Youtube API Key 추가
        $params['key'] = $this->YOUTUBE_API_KEY;
        
        $curl = new Curl();
        $curl->get($url, $params);
        $curl->close();

        return json_decode($curl->response, true);
    }

}
