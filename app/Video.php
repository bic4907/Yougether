<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoInfo;

class Video extends Model
{
    protected $fillable = [
        'user_id', 'video', 'room_id',
    ];

    public function info() {
        $videoInfo = VideoInfo::where('videoId', '=', $this->video)->first();
        if($videoInfo) return $videoInfo;

        return null;
    }
}
