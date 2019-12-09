<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Room\Lobby;
use App\Room;
use Illuminate\Support\Facades\Redis;

class roomDeleteCron extends Command
{
    protected $signature = 'room:delete';
    protected $description = 'If entered number is zero, delete the room';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $room_info = Room::get();

        for($i=0;$i<sizeof($room_info);$i++){
            if(Redis::get($room_info[$i]->id) == 0){
                Room::where('id', $room_info[$i]->id)->delete();
            }
        }
    }
}
