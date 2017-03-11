<?php

namespace App\Http\Controllers\Home;

use App\Store\DailyMoodStore;
use App\Store\UserMsgStore;
use App\Store\UserStore;
use App\Tools\Common;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Redis;
use Validator;
use Hash;
use Auth;
use DB;
use Cache;
use App\Store\UserLoginStore;

class HomeController extends Controller
{
    //用户发表每日心情
    public function index(Request $request)
    {

        return view('home.index');
    }





}
