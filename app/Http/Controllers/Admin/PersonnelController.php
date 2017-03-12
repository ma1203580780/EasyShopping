<?php

namespace App\Http\Controllers\Admin;


use App\Services\CommonService;
use App\Store\AdminStore;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class PersonnelController extends Controller
{
    //后台首页
    public function index(Request $request)
    {
        $data = $request->all();
        //当前页码
        if (empty($data['nowPage'])) {
            $nowPage = 1;
        } else {
            $nowPage = $data['nowPage'];
        }
        $result = AdminStore::getAll($nowPage);
        return view('admin.index', ['datas' => $result]);
    }


    public function show(){

    }

    public function status($id)
    {
        $blog = AdminStore::getFirst(['id'=>$id]);
        $up = $blog->status == 1?2:1;
        $re = AdminStore::adminUpdate(['id'=>$id],['status'=>$up]);
        if($re){
            return $up;
        }
        return $re;
    }




}
