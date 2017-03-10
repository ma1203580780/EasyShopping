<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

//首页


//邮件发送
Route::get('mail/send','Mail\MailController@send');

//测试redis 缓存
Route::get('/test', function () {
    //向有序集合中插入
    Redis::ZADD('fin_test',88,"Li Lei");
    //取出
    $l = Redis::ZSCORE('fin_test',"Li Lei");
    $r = Redis::ZSCORE('fin_test',"Li");
    dd($l,$r);
    return 1;
});



















