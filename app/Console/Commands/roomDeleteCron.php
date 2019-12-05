<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Room\LobbyController;
use App\Room;

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
        $room_info = LobbyController::roomInformation();

        if(!empty($room_info[1])){
            for ($i = 0; $i < sizeof($room_info[1]); $i++) {
                if ($room_info[1][$i] == 0) {
                    Room::where('id', $room_info[0][$i]->id)->delete();
                }
            }
        }
    }
}
