<?php

namespace Tests\Feature;

use App\Http\Controllers\API\RecommendedVideoController;
use App\Http\Controllers\API\VideoInfoParserController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoInfoParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetOneVideoInfo()
    {
        var_dump ((new VideoInfoParserController)->getVideoInfo('l502xg11uNM'));

        $this->assertTrue(true);
    }

    public function testGetNextVideoInfo()
    {
        var_dump ((new RecommendedVideoController)->getNextVideoInfo('l502xg11uNM'));

        $this->assertTrue(true);
    }
}
