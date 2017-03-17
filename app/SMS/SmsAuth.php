<?php

namespace App\SMS;

use Cache;
/**
*   使用方法:为手机短信验证服务
*/
class smsAuth {

    public function smsAuth($action, $phone)
    {
        //阿里大鱼的两个key
        $appkey = env('SMS_KEY','');
        $secretkey = env('SMS_SECRET','');
        //创建短信验证类
        $alisms = new \App\SMS\AliSms($appkey, $secretkey, '', '');
        //生成随机的验证码
        $code = rand(100000,999999);
        //创建短信内容信息数组
        $smsarr=array();
        //判断用户行为
        switch ($action) {
            case '身份认证':
                $smsarr=['data' => ['code' => strval($code)], 'code' => 'SMS_53675005'];
                break;
            case '变更密码':
                $smsarr=['data' => ['code' => strval($code)], 'code' => 'SMS_53530027'];
                break;
            case '登录':
                $smsarr=['data' => ['code' => strval($code)], 'code' => 'SMS_53665036'];
                break;
            case '注册':
                $smsarr=['data' => ['code' => strval($code)], 'code' => 'SMS_53595034'];
                break;
            default:
                return '数据出错,发送失败！';
                break;
        }
        //得到结果
        $result = $alisms->sign($action)->data($smsarr['data'])->code($smsarr['code'])->send($phone);
        //将返回的json数据转成数组
        $result = json_decode($result,true);
        //根据返回的json数据信息判断是否发送成功，并输出内容
        foreach ($result as $key => $value) {
            if($key == 'error_response'){
                return [false,'发送失败，'.$value['sub_msg'].'，请重新发送！'];
            }elseif($key == 'alibaba_aliqin_fc_sms_num_send_response' && $value['result']['success'] == '1'){
//                Cache::put($phone.$action, $code ,10);
                cache(["STRING:USER:".$phone.":".$action => $code], 30);//用于设置缓存值    有效期为30分钟
                return [true,'发送成功'];
            }else{
                return [false,'发送失败，请重新发送！'];
            }
        }
    }
}
