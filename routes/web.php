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

Route::get('/', 'Room\LobbyController@show')->name('room.list');

Route::get('/user', 'User\LoginController@checkingSession')->name('checkingSession');
Route::post('/user', 'User\LoginController@settingSession')->name('settingSession');
Route::put('/user/keepalive', 'User\KeepAliveController@renew')->name('user.keepalive');

Route::post('/room', 'Room\CreateController@makingRoom')->name('room.create');

Route::get('room_get', 'Room\LobbyController@roomInformation')->name('room.get');

Route::get('room/{room_id}', 'Room\ShowController@show')->name('room.enter');
Route::post('room/{room_id}/chat', 'ChatController@send')->name('room.chat.send');

Route::get('room/{room_id}/video', 'Video\VideoController@getVideoList')->name('room.video.list');
Route::post('room/{room_id}/video', 'Video\VideoController@addVideo')->name('room.video.add');

Route::post('room/{room_id}/sync', 'Room\RoomSyncController@updateRoomSync')->name('room.chat.sync');

Auth::routes();
