<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

//首页
Route::get('/', function () {
    return "<h1>exam</h1>";
});

//邮件发送
Route::get('mail/send','MailController@send');

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

//恢复redis排名数据
Route::get('/recoveryRank', function () {
    //当前时间戳
    $timestamp = time();

    //本周起始时间
    $weekstart  = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
    //本周结束时间
    $weekend    = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));

    //本月开始时间
    $monthstart = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp)) . '-01'));
    //本月结束时间
    $monthend   = date("Y-m-d", strtotime("$monthstart +1 month -1 day"));

    //本季开始时间
    $season = ceil((date('n'))/3);//当月是第几季度
    $seasonStart= date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y')));
    //本季结束时间
    $seasonEnd  = date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y')));

    //年度开始时间
    $yearStart  = date('Y-01-01', strtotime(date('Y', $timestamp) . '-01-01'));
    //年度结束时间
    $yearEnd    = date('Y-12-31', strtotime(date('Y', $timestamp) . '-12-31'));


    //好了，开始从MySQL取数据了，这里后续加上chunk（200）。
    $data = DB::table('chaoniurank_upload_record')->get();
//    dd($data);
    foreach($data as $temp){
        //恢复person_week_rank有序集合
        if($temp->time > strtotime($weekstart)&& $temp->time < strtotime($weekend)){
            if(Redis::ZSCORE('person_week_rank',$temp->openid)){
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('person_week_rank',$temp->distance,$temp->openid);
            }else{
                //判断该member是否存在，不存在就添加
                Redis::ZADD('person_week_rank',$temp->distance,$temp->openid);
            }

        }

        //恢复person_month_rank有序集合
        if($temp->time > strtotime($monthstart)&& $temp->time < strtotime($monthend)) {
            if (Redis::ZSCORE('person_month_rank', $temp->openid)) {
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('person_month_rank', $temp->distance, $temp->openid);
            } else {
                //判断该member是否存在，不存在就添加
                Redis::ZADD('person_month_rank', $temp->distance, $temp->openid);
            }
        }

        //恢复person_quarter_rank有序集合
        if($temp->time > strtotime($seasonStart)&& $temp->time < strtotime($seasonEnd)) {
            if (Redis::ZSCORE('person_quarter_rank', $temp->openid)) {
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('person_quarter_rank', $temp->distance, $temp->openid);
            } else {
                //判断该member是否存在，不存在就添加
                Redis::ZADD('person_quarter_rank', $temp->distance, $temp->openid);
            }
        }

        //恢复person_year_rank有序集合
        if($temp->time > strtotime($yearStart)&& $temp->time < strtotime($yearEnd)) {
            if (Redis::ZSCORE('person_year_rank', $temp->openid)) {
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('person_year_rank', $temp->distance, $temp->openid);
            } else {
                //判断该member是否存在，不存在就添加
                Redis::ZADD('person_year_rank', $temp->distance, $temp->openid);
            }
        }

        //恢复group_week_rank有序集合
        if($temp->time > strtotime($weekstart)&& $temp->time < strtotime($weekend)){
            if(Redis::ZSCORE('group_week_rank',$temp->group_id)){
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('group_week_rank',$temp->distance,$temp->group_id);
            }else{
                //判断该member是否存在，不存在就添加
                Redis::ZADD('group_week_rank',$temp->distance,$temp->group_id);
            }

        }

        //恢复group_month_rank有序集合
        if($temp->time > strtotime($monthstart)&& $temp->time < strtotime($monthend)){
            if(Redis::ZSCORE('group_month_rank',$temp->group_id)){
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('group_month_rank',$temp->distance,$temp->group_id);
            }else{
                //判断该member是否存在，不存在就添加
                Redis::ZADD('group_month_rank',$temp->distance,$temp->group_id);
            }

        }

        //恢复group_quarter_rank有序集合
        if($temp->time > strtotime($seasonStart)&& $temp->time < strtotime($seasonEnd)){
            if(Redis::ZSCORE('group_quarter_rank',$temp->group_id)){
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('group_quarter_rank',$temp->distance,$temp->group_id);
            }else{
                //判断该member是否存在，不存在就添加
                Redis::ZADD('group_quarter_rank',$temp->distance,$temp->group_id);
            }

        }

        //恢复group_year_rank有序集合
        if($temp->time > strtotime($yearStart)&& $temp->time < strtotime($yearEnd)){
            if(Redis::ZSCORE('group_year_rank',$temp->group_id)){
                //判断该member是否存在，存在就自增
                Redis::ZINCRBY('group_year_rank',$temp->distance,$temp->group_id);
            }else{
                //判断该member是否存在，不存在就添加
                Redis::ZADD('group_year_rank',$temp->distance,$temp->group_id);
            }
        }

    }

    return 'ok';

});

//恢复redis个人和跑团数据
Route::get('/recoveryBasic', function () {
    //好了，开始从MySQL取数据了，这里后续加上chunk（200）。
    $data = DB::table('chaoniurank_users')->get();
    foreach($data as $temp) {
        //生成个人信息缓存
        Redis::HMSET($temp->openid,'run_name',$temp->run_name,'distance',$temp->distance,'group_id',$temp->group_id,'point',$temp->point);
        //生成跑团信息缓存
        Redis::HMSET('group_'.$temp->group_id,$temp->openid,$temp->group_level);
    }
    return 'ok!';

});


















