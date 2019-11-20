<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class userExitCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:exit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checking exit for browser';

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
        User::where('last_hit', '<', Carbon::now()->addSeconds(-3))->update(['room_id' => Null]);
    }
}
