<?php

namespace App\Http\Controllers\Admin;


use App\Services\CommonService;
use App\Store\AdminStore;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use DB;

class AdminController extends Controller
{
    //后台首页
    public function index()
    {
          return "后台首页";
    }

    public function login(){
        return view('admin.login');
    }

    //登录
    public function adminLogin(Request $request)
    {
        $param=$request->all();
        $password = $param['password'];
        //判断是否存在
        $userInfo = AdminStore::getFirst(['phone'=>$param['phone']]);
        if(count($userInfo) <1){
            return back()->withErrors('该账号不存在!');
        }
        //判断密码是否正确
        if($userInfo->password == CommonService::passMcrypt($password)){
            if($userInfo->status == 2){
                return back()->withErrors('账号被停用..');
            }
            session(['adminInfo'=>['phone'=>$param['phone'],'duties'=>$userInfo->duties,'guid'=>$userInfo->guid]]);
            return Redirect('/admin');
        }else{
            return back()->withErrors('密码错误..');
        }
    }

    public function show(){

    }




}
