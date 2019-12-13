<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserLog extends Model
{
    protected $fillable = [
        'user_id', 'room_id', 'rewind', 'add_video',
    ];

    public static function getUserAddCount($user_id, $room_id)
    {
        $videoAddCount = DB::table('user_logs')
            ->where('user_logs.user_id', '=', $user_id)
            ->where('user_logs.room_id', '=', $room_id)
            ->where('add_video', '=', true)
            ->get()
            ->count();

        if($videoAddCount == null) return 0;
        return $videoAddCount;
    }

    public static function getUserUpdateCount($user_id, $room_id)
    {
        $videoUpdateCount = DB::table('user_logs')
            ->where('user_logs.user_id', '=', $user_id)
            ->where('user_logs.room_id', '=', $room_id)
            ->where('rewind', '=', true)
            ->get()
            ->count();

        if($videoUpdateCount == null) return 0;
        return $videoUpdateCount;
    }
}
