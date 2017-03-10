<?php

namespace App\SMS;

use Cache;
/**
*   使用方法:为手机短信验证服务
*/
class smsAuth {
    /**
    *   生成手机验证码
    *   常用短信模板：
     * 	系统身份验证验证码	SMS_47520070    *
     *
    -	系统短信测试	    SMS_47520069
     *
    -	系统登录确认验证码	SMS_47520068	*
     *
    -	系统登录异常验证码	SMS_47520067
     *
    -	系统用户注册验证码	SMS_47520066	*
     *
    -	系统活动确认验证码	SMS_47520065
     *
    -	系统修改密码验证码	SMS_47520064	*
     *
    -	系统信息变更验证码	SMS_47520063
    */
    public function smsAuth($action, $phone)
    {
        //阿里大鱼的两个key
        $appkey='23640405';
        $secretkey='c7c2560bf0487357c6bc3146acc937d8';
        //创建短信验证类
        $alisms = new \App\SMS\AliSms($appkey, $secretkey, '', '');
        //生成随机的验证码
        $code = rand(100000,999999);
        //创建短信内容信息数组
        $smsarr=array();
        //判断用户行为
        switch ($action) {
            case '注册验证':
                $smsarr=['data' => ['code' => strval($code), 'product' => '超牛平台'], 'code' => 'SMS_47520066'];
                break;
            case '变更验证':
                $smsarr=['data' => ['code' => strval($code), 'product' => '超牛平台'], 'code' => 'SMS_47520064'];
                break;
            case '登录验证':
                $smsarr=['data' => ['code' => strval($code), 'product' => '超牛平台'], 'code' => 'SMS_47520068'];
                break;
            case '身份验证':
                $smsarr=['data' => ['code' => strval($code), 'product' => '超牛平台'], 'code' => 'SMS_47520070'];
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
                cache([$phone.$action => $code], 30);//用于设置缓存值    有效期为30分钟
//                cache([$phone."validity"=>1],1);     //用户请求验证短信  有效期为1分钟
                return [true,'发送成功'];
            }else{
                return [false,'发送失败，请重新发送！'];
            }
        }
    }
}
