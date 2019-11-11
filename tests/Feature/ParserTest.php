<?php

namespace Tests\Feature;

use App\Http\Controllers\API\ParserController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        var_dump((new ParserController)->getJSON(
            'https://www.googleapis.com/youtube/v3/videos',
            array(
                'id'=>'l502xg11uNM',
                'part'=>'snippet',

            )
        ));


        $this->assertTrue(true);
    }
}
