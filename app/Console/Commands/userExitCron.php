<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class userExitCron extends Command
{
    protected $signature = 'user:exit';
    protected $description = 'checking exit for browser';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $room_id = User::where('last_hit', '<', Carbon::now()->addSeconds(-3))->select('room_id')->first();
        if(!empty($room_id)) {
            Redis::set($room_id->room_id, Redis::get($room_id->room_id) - 1);
        }
        User::where('last_hit', '<', Carbon::now()->addSeconds(-3))->update(['room_id' => Null]);
    }
}
