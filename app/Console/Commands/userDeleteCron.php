<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;

class userDeleteCron extends Command
{
    protected $signature = 'user:delete';
    protected $description = 'check last update and delete';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::where('last_hit', '<', Carbon::now()->addDays(-1))->delete();
    }
}
