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

Route::get('/', 'Room\Lobby@show')->name('room.list');

Route::get('/user', 'User\Login@checkingSession')->name('checkingSession');
Route::post('/user', 'User\Login@settingSession')->name('settingSession');
Route::put('/user/keepalive', 'User\KeepAlive@renew')->name('user.keepalive');

Route::post('/room', 'Room\Create@makingRoom')->name('room.create');

Route::get('room_get', 'Room\Lobby@roomInformation')->name('room.get');

Route::get('room/{room_id}', 'Room\Enter@show')->name('room.enter');
Route::post('room/{room_id}/chat', 'ChatController@send')->name('room.chat.send');

Route::get('room/{room_id}/video', 'Video\ListController@getList')->name('room.video.list');
Route::post('room/{room_id}/video', 'Video\AddController@addVideo')->name('room.video.add');

Route::post('room/{room_id}/sync', 'Room\SyncController@renew')->name('room.chat.sync');

Auth::routes();
