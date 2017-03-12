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


//中间件验证登录
Route::group(['middleware' => 'UserMiddleware'], function () {

    //首页
    Route::resource('/','Home\HomeController');

    Route::group(['namespace' => 'Good'], function(){
        //管理
        Route::resource('/good','GoodController');
        //禁用
        Route::resource('/good/status/{{id}}/','GoodController@status');
    });

});

Route::get('/admin/login','Admin\AdminController@login');
Route::post('/adminLogin','Admin\AdminController@adminLogin');

    // 后台中间件验证登录
Route::group(['middleware' => 'AdminMiddleware'], function () {

    Route::group(['namespace' => 'Admin'], function(){
        //管理
        Route::resource('/admin','AdminController');
        //人员管理
        Route::resource(' /personnel','PersonnelController');
        //禁用
        Route::resource('/personnel/status/{{id}}/','PersonnelController@status');

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














