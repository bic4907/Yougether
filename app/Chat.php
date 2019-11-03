<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'user_id', 'text', 'room_id',
    ];

    public function users()
    {
        return $this->hasMany('App/User');
    }
}
