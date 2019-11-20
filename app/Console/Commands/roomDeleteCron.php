<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Room\LobbyController;
use App\Room;

class roomDeleteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'room:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If entered number is zero, delete the room';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
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
