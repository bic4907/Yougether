<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KeepAlive extends Controller
{
    function renew() {
        $user = Auth::user();
        $user->last_hit = Carbon::now();
        $user->save();
    }
}
