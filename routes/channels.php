<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::routes();
/*
Broadcast::channel('chat.{roomId}', function ($roomId) {
    return true;
    //return (int) $user->id === (int) $user_id && (int) $user->romm_id === (int) $room_id;
}, ['guards'=>['web', 'admin']]);
*/