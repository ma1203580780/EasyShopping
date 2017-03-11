<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

Route::group(['namespace' => 'User'], function(){
    //验证码
    Route::get('/code/captcha/{tmp}', 'UserController@captcha');
    //短信验证码
    Route::post('/smsauth','UserController@smsauth');
    //用户登录页面
    Route::get('/login','UserController@loginView');
    //用户登录
    Route::post('/login','UserController@login');
    //用户注册页面
    Route::get('/register','UserController@registerView');
    //用户注册
    Route::post('/register','UserController@register');
});

Route::group(['middleware' => 'UserMiddleware'], function () {

    //首页
    Route::resource('/','Home\HomeController');

    Route::group(['namespace' => 'User'], function(){
        //找回密码
        Route::resource('/retrievePwd','UserController@retrievePwd');
        //用户信息管理
        Route::resource('/userInfo','UserController@userInfo');
        //用户头像上传
        Route::resource('/upload','UserController@upload');
        //OSS密钥
        Route::resource('/sts','UserController@sts');
        //修改密码
        Route::resource('/pwd','UserController@pwd');
    });

});



////测试redis 缓存
//Route::get('/555', function () {
//    cache(['555'=>'666'],3);
//    return cache('555');
//});
//
////测试mysql
//Route::get('/666', function () {
//    $res = DB::table('users')->get();
//    return $res;
//});














