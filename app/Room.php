<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'title', 'video_id', 'current_info',
    ];

    public function videos()
    {
        return $this->hasMany('App/Video');
    }

    public function users()
    {
        return $this->hasMany('App/User');
    }

    public function chats()
    {
        return $this->hasMany('App/Chat');
    }
}
