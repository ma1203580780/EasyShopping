<?php

namespace App\Http\Controllers\Blog;

use App\Services\OSS;
use App\Store\BlogStore;
use App\Store\CateStore;
use App\Store\UserStore;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        //当前页码
        if (empty($data['nowPage'])) {
            $nowPage = 1;
        } else {
            $nowPage = $data['nowPage'];
        }
        $result = BlogStore::getAll($nowPage);
        foreach($result as $key=>$value){
            $cate = CateStore::getFirst(['id'=>$value->cate_id]);
            $result[$key]->cate_name = $cate->cate;
            $author = UserStore::getFirst(['guid'=>$value->user_id]);
            $result[$key]->author = $author->username;
        }
        return view('blog.index', ['datas' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = CateStore::getAll();
        return view('blog.create', ['cate' => $data]);
    }

    /*
     * 视频上传
     * */

    public function gmt_iso8601($time) {
        $dStr = date('Y-m-d H:i:s',$time);
        $expiration = str_replace(" ","T",$dStr);
        return $expiration."Z";
    }

    public function ueditor(){
        return view('blog.ueditor');
    }

    public function upload()
    {
        $id= 'itWoGzGGLnygJB7t';
        $key= 'QNv1ySN0lR4YyEhv31YMalY3ZuJdsC';
        $host = 'http://weiyuyan.oss-cn-beijing.aliyuncs.com';

        $now = time();
        $expire = 30; //设置该policy超时时间是30s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);
        $dir = 'news/video/';

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input=$request->all();
          $rules = [
              'title'=>'required',
              'content'=>'required',
              'cate_id'=>'required',
          ];

          $message = [
              'title.required'     =>'名称不能为空！',
              'content.required'   =>'内容不能为空！',
              'cate_id.required'      =>'分类不能为空！',
          ];
          $validator = Validator::make($input,$rules,$message);

          if($validator->passes()) {
             $loginInfo = session('loginInfo');
            $param = [
                'user_id'=>$loginInfo['guid'],
                'title' =>$input['title'],
                'cate_id'=>$input['cate_id'],
                'content'=>$input['content'],
              ];

            $result = BlogStore::blogInsert($param);

            if ($result) {
                return Redirect('/blog');
            }else{
                return back()->withErrors('网络不好，再试试？');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Common::curl('/news/'.$id,'', 0);
//            echo $result;die;
        $json = json_decode($result);
//        dd($json);
        return view('admin.news.show', ['data' => $json->ResultData]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cate_json = Common::curl('/news_cate',null, 0);
        $cate = json_decode($cate_json);
        $news_json = Common::curl('/news/'.$id,'', 0);
        $news = json_decode($news_json);
        return view('admin.news.create', ['cate' => $cate->ResultData,'new' => $news->ResultData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $param = $request->all();
        $json = Common::curl('/news/update/'.$id,$param, 0);
//        echo $json; die;
        return Redirect('news');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function status($id)
    {
        $blog = BlogStore::getFirst(['id'=>$id]);
        $up = $blog->status == 1?2:1;
        $re = BlogStore::blogUpdate(['id'=>$id],['status'=>$up]);
        if($re){
            return $up;
        }
        return $re;

    }


}
