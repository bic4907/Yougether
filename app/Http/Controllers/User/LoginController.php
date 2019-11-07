<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function checkingSession(Request $request) {
        if(!$request->session()->get('nickname')) {
            $request->session()->put('nickname', $request->nickname);

            return null;
        }
        else{
            if(!User::where('nickname', $request->session()->get('nickname'))->first()){
                return null;
            }
            return $request->session()->get('nickname');
        }
    }

    public function settingSession(Request $request){
        if(!$request->session()->get('nickname')){
            $request->session()->put('nickname', $request->nickname);
            $this->checkingRegistered($request);
            return $request->cookie('yougether_session');

        }
        else{
            $old_name = $request->session()->get('nickname');
            $request->session()->put('nickname', $request->nickname);
            $this->updatingNickname($old_name, $request);
        }
    }

    public function signUp($user){

        $todo_user = new User();
        $todo_user->nickname = $user->nickname;

        $todo_user->save();
    }

    public function updatingNickname($old_name, $request){
        User::where('nickname', $old_name)->update(['nickname' => $request->nickname]);
    }

    public function checkingRegistered($request)
    {
        $user = User::where('nickname', $request->nickname)->get();
        if(!sizeof($user)){
            $this->signUp($request);
        }else{
            throw new Exception("Value must be 1 or below");
        }
    }
}
