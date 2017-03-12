<?php

namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    //首页
    public function index()
    {
        return redirect('/good');
    }





}
