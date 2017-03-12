<?php

namespace App\Http\Controllers\Good;

use App\Store\GoodStore;
use App\Store\CateStore;
use App\Store\UserStore;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = GoodStore::getAll();
        return view('good.index', ['datas' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('good.create');
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
              'good_name'=>'required',
              'good_price'=>'required',
          ];

          $message = [
              'good_name.required'     =>'商品名称不能为空！',
              'good_price.required'   =>'商品价格不能为空！',
          ];
          $validator = Validator::make($input,$rules,$message);

          if($validator->passes()) {
             $loginInfo = session('loginInfo');
            $param = [
                'name'=>$input['good_name'],
                'price' =>$input['good_price']*100,
              ];

            $result = GoodStore::goodInsert($param);

            if ($result) {
                return Redirect('/good/create');
            }else{
                return back()->withErrors('网络不好，再试试？');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    /**
     * @param $id
     * @return int
     * 更改使用状态
     */
    public function status($id)
    {
        $good = GoodStore::getFirst(['id'=>$id]);
        //更新后的状态
        $up = $good->status == 1?2:1;
        $re = GoodStore::GoodUpdate(['id'=>$id],['status'=>$up]);
        if($re){
            return $up;
        }
        return $re;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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



}
