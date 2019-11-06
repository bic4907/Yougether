<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSentEvent;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    function send($room_id, Request $request)
    {
        $text = $request->post('text');
        $user_id = $request->post('user_id');
        $user = User::findOrFail($user_id);
        $nickname = $user->nickname;

        $chat = new Chat();
        $chat->user_id = $user_id;
        $chat->text = $text;
        $chat->room_id = $room_id;
        $chat->save();


        broadcast(new MessageSentEvent($room_id, $nickname, $text));
    }

    function receive(Request $request)
    {

    }
}
