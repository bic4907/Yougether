<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function checkingSession(Request $request) {
        if(!Auth::user()) {
            return Null;
        }
        else{
            return Auth::user()->nickname;
        }
    }

    public function settingSession(Request $request) {
        if(!Auth::user()){

            $this->signUp($request->nickname);
            return $request->cookie('yougether_session');
        }
        else{
            $this->updatingNickname($request->nickname);
        }
    }

    public function signUp($nickname){
        $todo_user = new User();
        $todo_user->nickname = $nickname;

        $todo_user->save();
        Auth::loginUsingId($todo_user->id);
    }

    public function updatingNickname($nickname){
        User::where('nickname', Auth::user()->nickname)->update(['nickname' => $nickname]);
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
