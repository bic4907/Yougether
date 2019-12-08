<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSentEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    function send($room_id, Request $request)
    {
        $text = $request->post('text');

        $user = Auth::user();
        if($user == null) abort(403);

        $chat = new Chat();
        $chat->nickname = $user->nickname;
        $chat->text = $text;
        $chat->room_id = intval($room_id);
        $chat->save();

        broadcast(new MessageSentEvent($room_id, $user->nickname, $text)); //채팅 전송 이벤트 발생 -> 브로드캐스트
    }
}
