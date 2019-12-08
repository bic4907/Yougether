<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'nickname', 'last_hit'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function checkUserAddCount($user_id, $room_id)
    {
        $videoAddCount = DB::table('user_logs')
            ->where('user_logs.user_id', '=', $user_id)
            ->where('user_logs.room_id', '=', $room_id)
            ->where('add_video', '=', true)
            ->count();

        if ($videoAddCount > 2) //같은 방에 비디오 추가 2회이상 -> 추가 불가
            return false;
        else if ($videoAddCount <= 2)
            return true;
    }

    public static function checkUserUpdateCount($user_id, $room_id)
    {
        $videoUpdateCount = DB::table('user_logs')
            ->where('user_logs.user_id', '=', $user_id)
            ->where('user_logs.room_id', '=', $room_id)
            ->where('rewind', '=', true)
            ->count();

        if ($videoUpdateCount > 2) //같은 방에 비디오 수정 2회이상 -> 추가 불가
            return false;
        else if ($videoUpdateCount <= 2)
            return true;
    }
}
