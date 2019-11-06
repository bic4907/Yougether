<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function checkingSession(Request $request){
        if(!$request->session()->get('nickname')){
            $request->session()->put('nickname', $request->nickname);

            $this->checkingRegistered($request);

            echo $request->cookie('yougether_session');
        }
        else{
            echo $request->session()->get('nickname');
        }
    }

    public function signUp($user){
        $todo_user = new User();
        $todo_user->nickname = $user->nickname;

        $todo_user->save();
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
