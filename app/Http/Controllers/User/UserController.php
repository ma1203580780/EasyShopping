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
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 该方法是用户注册时的手机验证的接口
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

    /**
     * @param $param
     * @return \Illuminate\Http\JsonResponse|string
     * 判断用户行为
     */
    public function smsAction($param){

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
     * 用户注册
     */
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 登录页面
     */
    public function loginView()
    {
        return view('home.login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 注册页面
     */

    public function registerView()
    {
        return view('home.register');
    }

    /**
     * @param $tmp
     * 验证码生成
     */
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

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 退出登录
     */
    public function out()
    {
        //删除session
        \session::forget('loginInfo');
        return redirect('/');
    }


    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 用户登录
     */
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
            session(['loginInfo'=>['phone'=>$param['phone'],'guid'=>$userInfo->guid]]);
            return Redirect('/');
        }else{
            return back()->withErrors('密码错误..');
        }
    }


}
