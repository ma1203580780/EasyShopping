<?php

namespace App\Http\Controllers\Api\User;

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

class UserCenterController extends Controller
{
    //用户发表每日心情
    public function dailyMood(Request $request)
    {
        //验证
        $data=$request->all();
        //获取用户guid
        $guid = $data['guid'];
        $param=json_decode($data['param'],true);
        //判断请求参数 mood 是否为空
        if(empty($param['mood'])){
            $lastMood = DailyMoodStore::getInfo($guid);
            $lastMood = Common::objectToArray($lastMood);
            if(!$lastMood)  $lastMood['mood'] = '';
                //            dd($lastMood);
            return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>['Message'=>'获取信息成功','data'=>$lastMood]]);
        }
        //如果是要更新近日心情说说
        $inParam = [
            'add_time' => time(),
            'user_id'  => $guid,
            'mood'     => $param['mood']
        ];
        $result = DailyMoodStore::moodInsert($inParam);
        if($result){
            return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>['Message'=>'发表心情成功!']]);
        }else{
            return response()->json(['serverTime'=>time(),'ServerNo'=>3,'ResultData'=>['Message'=>'发表心情失败，请稍后再试!']]);
        }


    }





}
