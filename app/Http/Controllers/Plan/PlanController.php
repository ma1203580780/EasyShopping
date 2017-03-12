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
        //当挑选的买的东西数量小于种类时，返回错误
        if($data['goods_count_max'] < count($data['chk_value'])){
            return json_encode('件数错误！');
        }
        //整理数据
//        $in =[
//            'guid'=>$data['plan_guid'],  //采购计划guid
//            'pay'=>$data['pay']*100,  //预算(单位：分)
//            'goods_count_max'=>$data['goods_count_max'],  //采购最大量
//            'goods_count_min'=>$data['goods_count_min'],  //采购最小量
//        ];
//        //将计划信息存储到数据库
//        $re = PlanStore::planInsert($in);

        //计算采购计划

        $rel = array();//单商品约束条件
        $oneGoodSum = 0;//单件商品加一起的价格
        foreach($data['chk_value'] as $k=>$v){

            //单个商品的采购约束条件
            $rel[$k] = RelPlanStore::getFirst(['plan_guid'=>$data['plan_guid'],'master_good_id'=>$v]);

            //商品单价
            $p = GoodStore::getFirst(['id'=>$v]);   $price[$k] = $p->price;

            //单件商品加一起的价格
            $oneGoodSum = $p->price+$oneGoodSum;

            //最终各种商品购买数量的结果集
            $buyResult[$k] = 1;

        }
        //每样都购买一件，余额为：
        $yue = $data['pay']-$oneGoodSum;
        if($yue < 0){
            return json_encode('预算不足以购买最低件数的指定商品！');
        }
            //单品约束条件
            $rel=array_filter($rel);
            //最大购买数
             $goods_count_max = empty($data['goods_count_max'])?10000000000:$data['goods_count_max'];
            //商品类型数
            $goodCate = count($data['chk_value']);
            //因为已选商品至少买一件，因此单件商品最多购买数为
            $singleNumMax = $data['goods_count_max']+1-$goodCate;
            //单件商品最小购买数为1


        foreach($buyResult as $v){

        }
    }
}
