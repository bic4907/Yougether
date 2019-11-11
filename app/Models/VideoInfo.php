<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoInfo extends Model
{
    protected $fillable = [
        'videoId', 'videoTitle', 'videoDesc', 'duration', 'tags', 'thumbnail'
    ];

}
