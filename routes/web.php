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

Route::get('/', function () {
    return view('lobby');
});


Route::get('room/{room_id}', function () {
    // 룸 컨트롤러 호출
    return view('room');
});
Route::post('room/{room_id}/chat', function () {
    // 룸 컨트롤러 호출

})->name('room.chat.send');
Route::get('room', function () {
    return view('room');
});

