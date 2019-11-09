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
        dd(Auth::user());
        $text = $request->post('text');

        $user_id = $request->session()->get('nickname');

        $user = User::where('nickname', '=', $user_id)->first();
        if($user == null) abort(403);

        $chat = new Chat();
        $chat->nickname = $user->nickname;
        $chat->text = $text;
        $chat->room_id = intval($room_id);
        $chat->save();

        broadcast(new MessageSentEvent($room_id, $user->nickname, $text));
    }

    function receive(Request $request)
    {

    }
}
