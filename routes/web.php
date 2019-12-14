<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', 'Room\Lobby@show')->name('room.list');

Route::get('/user', 'User\Login@checkingSession')->name('checkingSession');
Route::post('/user', 'User\Login@settingSession')->name('settingSession');
Route::put('/user/keepalive', 'User\KeepAlive@renew')->name('user.keepalive');

Route::post('/room', 'Room\Create@makingRoom')->name('room.create');

Route::get('room_get', 'Room\Lobby@roomInformation')->name('room.get');

Route::get('room/{room_id}', 'Room\Enter@show')->name('room.enter');
Route::post('room/{room_id}/chat', 'ChatBroadCaster@send')->name('room.chat.send');

Route::get('room/{room_id}/video', 'Video\Manage@getVideoList')->name('room.video.list');
Route::post('room/{room_id}/video', 'Video\Manage@addVideo')->name('room.video.add');
Route::delete('room/{room_id}/video', 'Video\Manage@deleteVideo')->name('room.video.del');

Route::get('room/{room_id}/log/', 'UserLog\UserLogController@getUserUpdateCount')->name('room.log.count');
Route::post('room/{room_id}/log/add', 'UserLog\UserLogController@addUserUpdateVideoCount')->name('room.log.add');

Route::post('room/{room_id}/sync', 'Room\RoomSyncController@updateRoomSync')->name('room.chat.sync');

Auth::routes();
