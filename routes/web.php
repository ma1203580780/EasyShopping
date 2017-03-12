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

    //商品
    Route::group(['namespace' => 'Good'], function(){
        //管理
        Route::resource('/good','GoodController');
        //状态管理
        Route::post('/status/{id}/','GoodController@status');
    });
    //计划
    Route::group(['namespace' => 'Plan'], function(){

    Route::resource('/plan','PlanController');
    Route::resource('/relPlan/create','PlanController@relCreate');
    //递归测试
    Route::resource('/digui/{forNum}/{$singleNumMax}/','PlanController@digui');
        Route::resource('/d/{goodCate}/{singleNumMax}/','PlanController@firstGet');
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














