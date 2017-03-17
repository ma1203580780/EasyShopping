<?php

namespace App\Http\Controllers\Plan;

use App\Service\TxService;
use App\Services\CommonService;
use App\Store\GoodStore;
use App\Store\PlanStore;
use App\Store\ProgrammeStore;
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
            'good_count_min'=>empty($data['good_count_min'])?1:$data['good_count_min'],  //采购最小量
            'good_count_max'=>empty($data['good_count_max'])?1000:$data['good_count_max'],  //采购最大量
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

    /**
     * @param $data
     * @return bool
     * 向data_plan表插入数据
     */
    public function insertDataPlan($data){
        $in =[
            'guid'=>$data['plan_guid'],  //采购计划guid
            'pay'=>$data['pay']*100,  //预算(单位：分)
            'goods_count_max'=>$data['goods_count_max'],  //采购最大量
            'goods_count_min'=>$data['goods_count_min'],  //采购最小量
        ];
        //将计划信息存储到数据库
        return PlanStore::planInsert($in);
    }

    /**
     * @param $price
     * @param $pay
     * @param $singleNumMax
     * @param $goods_count_min
     * @param $goods_count_max
     * @return string
     * 拼接执行语句
     */
    public function makeCommond($price,$pay,$singleNumMax,$goods_count_min,$goods_count_max)
    {
        //下面开启元编程
        $commondStart = "";//for循环
        $variable = '$j';  //变量名
        $commondEnd = "";  //结尾括号
        $commondSumMoney = "0"; //最终花的钱
        $commondSNum ="0";
        $commondNum = "0";//最终购买商品的数量
        //循环层数为商品种类数
        foreach($price as $k=>$v){
            $commondStart = $commondStart."for($variable=1;$variable <= $singleNumMax;$variable++){";
            $commondEnd = $commondEnd.'}';
            $commondSumMoney = $commondSumMoney."+$variable*$v";
            $commondNum = $commondNum."+{$variable}";
            $commondSNum = $commondSNum.','.$variable;
            $variable = $variable.'j';
        }
        //总预算
        $pay = $pay*100;
        //判断并处理
        $judge = "if(($commondSumMoney) < $pay && ($commondNum) >= $goods_count_min && ($commondNum) <= $goods_count_max )".'{$result[]=["spend"=>'."$commondSumMoney".',"info"=>"'.$commondSNum.'"];}';
        //组装命令
        $commond = $commondStart.$judge.$commondEnd;
       return $commond;
    }

    //采购计划制定
    public function store(Request $request)
    {
        $data = $request->all();
        //当挑选的买的东西数量小于种类时，返回错误
        if($data['goods_count_max'] < count($data['chk_value'])){
            return json_encode('件数错误！');
        }
        //计算采购计划
        $rel = array();//单商品约束条件
        $oneGoodSum = 0;//单件商品加一起的价格
        foreach($data['chk_value'] as $k=>$v){
            //单个商品的采购约束条件
            $rel[$k] = RelPlanStore::getFirst(['plan_guid'=>$data['plan_guid'],'master_good_id'=>$v]);
            //商品单价
            $p = GoodStore::getFirst(['id'=>$v]);   $price[$v] = $p->price;//[good_id=>price]
            //单件商品加一起的价格
            $oneGoodSum = $p->price+$oneGoodSum;
            //最终各种商品购买数量的结果集
            $buyResult[$v] = 1;
        }
        //每样都购买一件，金额为：
        $yue = ($data['pay']*100)-$oneGoodSum;
        //返回预算过低
        if($yue < 0){
            return json_encode('预算不足以购买最低件数的指定商品！');
        }
        //最大购买数
         $goods_count_max = empty($data['goods_count_max'])?10000000000:$data['goods_count_max'];
        //最小购买数
        $goods_count_min = empty($data['goods_count_min'])?1:$data['goods_count_min'];
        //商品类型数
        $goodCate = count($data['chk_value']);
        //因为已选商品至少买一件，因此单件商品最多购买数为
        $singleNumMax = $data['goods_count_max']+1-$goodCate;
        //获得所需的遍历层数的循环语句
        $commond = $this->makeCommond($price,$data['pay'],$singleNumMax,$goods_count_min,$goods_count_max);
//        echo $commond;
        $result =array();
        eval($commond);
        if(count($result) == 0){
            return json_encode('没有可实施的采购计划！');
        }
        //组装good_id和采购数量的对应数组
        foreach($result as $k=>$temp){
            $reqHtml[$k]['spend']=$result[$k]['spend'];
            $sort = explode(',',$temp['info']);

            for($i=0;$i<count($data['chk_value']);$i++){
                    $reqHtml[$k][]=[
                    $data['chk_value'][$i]=>$sort[$i+1],
                    ];
                }
        }
        //单品约束筛选
        $reqHtml = $this->singleCont($rel, $reqHtml);
        //结果集降序排序
        $sortReqHtml = $this->sort($reqHtml);
        //返回view内容
        $html = $this->makeHTML($sortReqHtml);
        return json_encode($html);
    }

    /**
     * @param $b
     * @return array|bool
     * 排序算法
     */
    public function sort($b){
        //判断参数是否是一个数组
        if(!is_array($b)) return false;
        foreach ($b as $k){
            $new[] = $k;
        }
        $len=count($new);
        for($k=0;$k<=$len;$k++){
            for($j=$len-1;$j>$k;$j--){

                    if($new[$j]>$new[$j-1]){
                        $temp = $new[$j];
                        $new[$j] = $new[$j-1];
                        $new[$j-1] = $temp;
                    }
                }
            }
            return $new;
    }

    /**
     * @param $rel
     * @param array $reqHtml
     * @return array
     * 单品约束筛选,返回筛选之后的信息
     */
    public function singleCont($rel, $reqHtml = [])
    {
        $relCondition=array_filter($rel);
        $delFlag = false;
        //条件的遍历
        foreach ($relCondition as $relsi){
            $thisId = $relsi->master_good_id; //商品id
            $bl = $relsi->proportion;   //关联购买比例
            //结果集的遍历
            foreach($reqHtml as $k=>$value){
                for($i=0;$i<(count($value)-1);$i++){
                    $thiskey = array_keys($value[$i]);
                    //当商品good_id相同时，比较采购数量
                    $num =  (int)reset($value[$i]);
                    $min = $relsi->good_count_min;
                    $max = $relsi->good_count_max;

                    if($thiskey[0] == $thisId){
                        if($num > $max | $num < $min) {  $delFlag = true; }
                    }
                    //如果有该比例条件约束
                    if($bl != "/"){
                        $blnum = 0;
                        $r = '$blnum='."$bl;";
                        eval($r);
                        //关联比例是$blnum;
                        if($thiskey[0] == $thisId) $masterNum = $num;
                        if($thiskey[0] == $relsi->rel_good_id) $relNum =$num;
                        if(!empty($masterNum) && !empty($relNum)){
                            if($blnum !=  $masterNum/$relNum){ $delFlag = true; }
                        }
                    }
                }
                //如果单品条件不允许，那么就删除该元素
                if($delFlag){ unset($reqHtml[$k]); $delFlag = false;}
            }
        }
        return $reqHtml;
    }

    /**
     * @param $reqHtml
     * @param $result
     * @return string
     * 拼接返回的视图代码
     */
    public function makeHTML($reqHtml)
    {
        $msg = "<a href='javascript:save()' class='form-control'>选择方案并保存</a>";
        foreach($reqHtml as $k=>$value){
            $checkbox = "<input type='radio' name='success_plan' value='plan_$k' value=''>";
            $msg = $msg."<h5>".$checkbox."&nbsp;&nbsp;可执行的采购方案".($k+1)."为：</h5><p id='plan_$k'>";
            for($i=0;$i<(count($value)-1);$i++){
                foreach($value[$i] as $kkk=>$vvv) {
                    $good = GoodStore::getFirst(['id' => $kkk]);
                }
                $msg = $msg . $good->name . "购买" . $vvv . "件 &nbsp;";
            }
            $msg =  $msg."需花费".($reqHtml[$k]['spend']/100)."元</p><br>";
        }

        $reaultHtml = "<p>$msg</p><br>";
        $script = "<script> ".
            "function save(){".
            "var cn = '#'+$('input:radio[name=success_plan]:checked').val();".
            "var post = $(cn).html();".
            " $.post('/plan/save',{plan:post},function(result){ alert('保存成功'); });".
            "}</script>";
        return $reaultHtml.$script;
    }

    /**
     * @param Request $request
     * @return string
     * 保存计划方案
     */
    public function savePlan(Request $request){

        $res = ProgrammeStore::programmeInsert(['programme'=>$request->plan]);
        if($res){
            return json_encode(1);
        }
        return json_encode(0);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 已有的采购方案列表
     */
    public function programme(){
        $data =ProgrammeStore::getAll();
        return view('plan.programme', ['datas' => $data]);
    }


}














