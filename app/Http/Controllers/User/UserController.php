<?php

namespace App\Http\Controllers\User;

use App\Services\CommonService;
use App\Store\UserStore;
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
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class UserController extends Controller
{
    /*
    *   该方法是用户注册时的手机验证的接口
    */
    public function smsauth(Request $request)
    {
        //获得app端传过来的json格式的数据转换成数组格式
        $param=$request->all();
        $action=$this->smsAction($param);
        //new一个短信的对象
        $smsauth=new \App\SMS\smsAuth();
        $result=$smsauth->smsAuth($action,$param['phone']);
        //判断短信是否发送成功并且插入Redis
//        dd($result);
        if($result[0]){
            return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>['Message'=>'success!']]);
        }else{
            return response()->json(['serverTime'=>time(),'ServerNo'=>1,'ResultData'=>['Message'=>$result[1]]]);
        }
    }

    public function smsAction($param){
        //判断用户行为
        switch ($param['action']) {
            case 1:
                $action="身份验证";
                break;
            case 2:
                $action="变更密码";
                break;
            case 3:
                $action="登录";
                break;
            case 4:
                $action="注册";
                break;
            default:
                return response()->json(['serverTime'=>time(),'ServerNo'=>1,'ResultData'=>['Message'=>'短信行为异常']]);
                break;
        }
        return $action;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    //用户注册
    public function register(Request $request)
    {
        //获得app端传过来的json格式的数据转换成数组格式
        $param=$request->all();
        $codeIndex = "STRING:USER:".$param['phone'].':注册';

        //获取cache中的验证码
        $code = cache($codeIndex, null);
        //判断用户输入的验证码是否正确
        if($code != $param['code']){
            return back()->withErrors('验证码错误..');
        }
        //判断是否注册过
        $exist = UserStore::getFirst(['phone'=>$param['phone']]);
        if($exist){
            return back()->withErrors('该帐号已注册..');
        }
        //生成用户的uuid
        $uuid = Uuid::uuid1()->getHex();
        $param = [
            'guid'=>$uuid,
            'phone'=>$param['phone'],
            'password'=>CommonService::passMcrypt($param['password']),
        ];
        $result = UserStore::userInsert($param);
        if($result){
            return Redirect('/login');
             }else{
            return back()->withErrors('未知错误..');
              }
    }

    public function loginView()
    {
        return view('home.login');
    }

    public function registerView()
    {
        return view('home.register');
    }

    // 验证码生成
    public function captcha($tmp)
    {
        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);
        //设置背景颜色
        $builder->setBackgroundColor(220, 220, 220);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        // dd($phrase);
        //把内容存入session
        \Session::flash('code', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function out()
    {
        //删除session
        \session::forget('loginInfo');
        return redirect('/');
    }


    //用户登录
    public function login(Request $request)
    {
        $param=$request->all();
        $password = $param['password'];
        //判断是否注册过
        $userInfo = UserStore::getFirst(['phone'=>$param['phone']]);
        if(count($userInfo) <1){
            return back()->withErrors('该帐号没有注册!');
         }
        //判断密码是否正确
        if($userInfo->password == CommonService::passMcrypt($password)){
            session(['loginInfo'=>['phone'=>$param['phone'],'star'=>$userInfo->star,'guid'=>$userInfo->guid]]);
            return Redirect('/');
        }else{
            return back()->withErrors('密码错误..');
        }
    }

    //找回密码(发送验证短信)
    public function retrievePwd(Request $request)
    {
        //获得app端传过来的json格式的数据转换成数组格式
        $data=$request->all();
        $param=json_decode($data['param'],true);
        //此处先用'注册验证'这个功能
        $codeIndex = $param['phone'].'变更验证';
        //获取cache中的验证码
        $code = cache($codeIndex, null);
        //判断用户输入的验证码是否正确
        if($code != $param['code']){
            return response()->json(['serverTime'=>time(),'ServerNo'=>1,'ResultData'=>['Message'=>'验证码错误!']]);
        }
        //验证手机号正确
        $userInfo = UserLoginStore::getFirst(['username'=>$param['phone']]);
        if(count($userInfo) <1){
            return response()->json(['serverTime'=>time(),'ServerNo'=>1,'ResultData'=>['Message'=>'该帐号未注册!']]);
        }
        //设置新密码
        $password = Common::passMcrypt($param['password']);
        //生成新的token，
        $token = Uuid::uuid1()->getHex();
        //更新用户令牌和密码
        UserLoginStore::userUpdate(['username'=>$param['phone']],['token'=>$token,'password'=>$password]);
        //用户的TOKEN存入缓存
        cache([$param['phone'].'token' => $token],60*24*30*3);//用户token 有效期3个月
        //获取用户的跑团id，name，比赛id
        $user = UserStore::getInfo(['user_id'=>$userInfo->user_id]);
        $group = GroupStore::getFirst(['group_id'=>$user->group_id]);
        $game = GameStore::getFirst(['game_id'=>$user->game_id]);
        $res = [
            'Message'=>'找回密码成功',                  //返回信息
            'group_name' => $user->group_name,     //获取用户的跑团name
            'group_id'=> $user->group_id,          //获取用户的跑团id
            'game_id'=>$user->game_id,             //获取用户的比赛id
            'guid'=>$userInfo->user_id,            //获取用户id
            'token'=>$token,                       //获取用户的token
            'level'=>$userInfo->level,             //获取用户的等级
            'phone'=>$userInfo->username,          //获取用户的手机号
            'logged'=>1,                           //获取用户的登录状态
            'group_head_img'=>empty($group)?'':$group->group_img,     //获取用户的跑团图片
            'group_info'=>empty($group)?'':$group->info,             //获取用户的跑团详情
            'game_name'=>empty($game)?'':$game->name,               //获取用户的比赛名称
            'game_info'=>empty($game)?'':$game->info,               //获取用户的比赛介绍
            'game_start'=>empty($game)?'':$game->start_time,        //获取参加比赛的开始时间
        ];
        return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>$res]);
    }

    /**
     * 上传oss回调
     */
    public function upload(Request $request){
//        //验证
//        $data=$request->all();
//        //获取用户guid
//        $guid = $data['guid'];
//        //登录表进行验证，是否存在
//        $userInfo = UserLoginStore::getFirst(['user_id'=>$guid]);
//        if(count($userInfo) <1){
//            return response()->json(['serverTime'=>time(),'ServerNo'=>1,'ResultData'=>['Message'=>'该帐号未注册!']]);
//        }
//        $param=json_decode($data['param'],true);
//        $signature=md5(trim($request->path()).trim($request['time']).trim($request['guid']).trim($request['param']).$userInfo->token);
////        echo $signature."<br>";
//        //验证签名
//        if ($request['signature'] != $signature) {
//            return response()->json(['serverTime'=>time(),'ServerNo'=>5,'ResultData'=>['Message'=>'signature is error!']]);
//        }
        //我本人的alioss密钥
//        $id= 'LTAIQqX3X2x7eqqt';
//        $key= 'CUF0r1AEloR4tY9spoL2qpUyefY7dy';
//        $host = 'http://chaoniu.img-cn-shanghai.aliyuncs.com';
        $id ='LTAIiMaefG2fywZd';
        $key= 'bWqL5mK4AdivvHKQda3bgMNFc8AST7';
        //此处用img代替oss
        $host = 'http://deci.img-cn-hangzhou.aliyuncs.com';
        //o阿里oss的保存目录
        $dir = 'user/headImg/';

        $now = time();
        $expire = 30; //设置该policy超时时间是30s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        echo json_encode($response);

    }

	/*
    *   该方法是为APP提供上传的sts验证
    */
    public function sts()
    {
        //调用sts的定义类
        $sts=new \App\STS\Appsts();
        //返回sts验证
        return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>[$sts->stsauth()]]);
    }

    //修改密码
    public function pwd(Request $request)
    {
        //获得app端传过来的json格式的数据转换成数组格式
        $data=$request->all();
        $param=json_decode($data['param'],true);
        //验证手机号正确
        $userInfo = UserLoginStore::getFirst(['username'=>$param['phone']]);
        if(count($userInfo) <1){
            return response()->json(['serverTime'=>time(),'ServerNo'=>3,'ResultData'=>['Message'=>'该帐号未注册!']]);
        }
        $oldPwd = Common::passMcrypt($param['oldPwd']);
        //验证密码是否正确
        if($oldPwd != $userInfo->password){
            return response()->json(['serverTime'=>time(),'ServerNo'=>3,'ResultData'=>['Message'=>'原密码不正确!']]);
        }
        //新密码
        $newPwd = Common::passMcrypt($param['newPwd']);
        //更新密码
        UserLoginStore::userUpdate(['username'=>$param['phone']],['password'=>$newPwd]);
        return response()->json(['serverTime'=>time(),'ServerNo'=>0,'ResultData'=>['Message'=>'修改密码成功!']]);
    }

}
