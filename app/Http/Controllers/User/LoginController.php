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
        if(!$request->session()->get('key')){
            $request->session()->put('nickname', $request->nickname);

            return null;
        }
        else{
            return $request->cookie('yougether_session');
        }
    }

    public function signUp($user){
        $todo_user = new User();
        $todo_user->nickname = $user->nickname;

        $todo_user->save();
    }

    public function checkingRegitered(Request $request)
    {
        $user = User::where('nickname', $request->nickname)->get();
        if(!sizeof($user)){
            $this->signUp($request);
        }else{
            throw new Exception("Value must be 1 or below");
        }
    }
}
