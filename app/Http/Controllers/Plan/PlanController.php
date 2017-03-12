<?php

namespace App\Http\Controllers\Plan;

use App\Services\CommonService;
use App\Store\GoodStore;
use App\Store\PlanStore;
use App\Store\RelPlanStore;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class PlanController extends Controller
{
    //采购计划首页
    public function index()
    {
        $goods = GoodStore::getAll(['status'=>1]);
        $guid = CommonService::getUuid();
        return view('plan.index', ['goods' => $goods,'plan_guid'=>$guid]);
    }

    //rel_plan表创建单个商品的采购约束
    public function relCreate(Request $request)
    {
        $data = $request->all();

        $insert =[
            'plan_guid'=>$data['plan_guid'],  //关联采购计划id
            'good_count_min'=>$data['good_count_min'],  //采购最小量
            'good_count_max'=>$data['good_count_max'],  //采购最大量
            'master_good_id'=>$data['master_good_id'],  //主商品good_id
            'rel_good_id'=>$data['rel_good_id'],        //关联商品good_id
            'proportion'=>$data['master_good_num']."/".$data['rel_good_num'], //购买比例
        ];

        $re = RelPlanStore::planInsert($insert);
        if($re){
            return json_encode('1');
        }else{
            return json_encode('2');
        }
    }

    //采购计划制定
    public function store(Request $request)
    {
        $data = $request->all();
        //整理数据
        $in =[
            'guid'=>$data['plan_guid'],  //采购计划guid
            'pay'=>$data['pay'],  //预算
            'goods_count_max'=>$data['goods_count_max'],  //采购最大量
            'goods_count_min'=>$data['goods_count_min'],  //采购最小量
        ];
        //将计划信息存储到数据库
        $re = PlanStore::planInsert($in);
        return $data['chk_value'];

//        return json_encode('1');
    }




}
