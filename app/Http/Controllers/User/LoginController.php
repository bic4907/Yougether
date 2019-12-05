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
            $this->checkingModel();
            return Auth::user()->nickname;
        }
    }

    public function checkingModel(){
        if(!sizeof(User::where('nickname', Auth::user()->nickname)->get())){
            return Null;
        }
    }

    public function settingSession(Request $request) {
        if(!Auth::user()){
            $this->checkingRegistered($request->nickname);
            return $request->nickname;
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

    public function checkingRegistered($nickname)
    {
        $user = User::where('nickname', $nickname)->get();
        if(!sizeof($user)){
            $this->signUp($nickname);
        }else{
            throw new Exception("Value must be 1 or below");
        }
    }
}
