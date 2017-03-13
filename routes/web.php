<?php

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

    //采购计划
    Route::group(['namespace' => 'Plan'], function(){

    Route::resource('/plan','PlanController');
    //创建计划
    Route::resource('/relPlan/create','PlanController@relCreate');
    //保存方案
    Route::resource('/plan/save','PlanController@savePlan');
    //方案列表
    Route::get('/programme','PlanController@programme');

    });

});














