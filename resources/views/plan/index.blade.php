@extends('admin.layouts.master')
@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header" >
          <h1>
            <i class="fa fa-calendar"></i>
              计划列表
          </h1>
          <ol class="breadcrumb">
             <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
             <li class="active">计划管理</li>
             <li class="active">计划列表</li>
          </ol>
        </section>
        @if(count($errors)>0)
            <div class="box-body">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i>错误：</h4>

                    @foreach($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>

            </div>
        @endif
        <!-- Main content -->
        <section class="content" >
			<div class="row">
            <div class="col-xs-12">


              <div class="box box-primary">

                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <td style="text-align: center" colspan="4">采购计划</td>
                    </tr>

                    <tr>
                        <td colspan="2"><span style="color: red;">*</span>预算(单位：元) <input id ='pay' type="text" name="pay"class="form-control" style="width:150px;display: inline-block"></td>

                        <td colspan="2"><span style="color: red;">*</span>商品总数
                            <input type="text" name="goods_count_min" id="goods_count_min" class="form-control" style="width:150px;display: inline-block" placeholder="最少">
                            ~
                            <input type="text" name="goods_count_max" id="goods_count_max" class="form-control" style="width:150px;display: inline-block" placeholder="最多">
                        </td>
                    </tr>




                      <tr>
                          <td style="text-align: center" colspan="4">选择商品</td>
                      </tr>
                    </thead>
                    <tbody>
                        @if(!empty($goods))
                        @foreach($goods as $key=>$data)
                            @if($key%4 === 0) <tr> @endif
                            <td><input type="checkbox" name = 'goods_id' value="{{$data->id}}" id="goods_id"  autocomplete ="off"><span  title="{{$data->price/100}}元/件">{{$data->name}}  {{$data->price/100}}元/件</span>&nbsp;<a href="javascript:define(good_id='{{$data->id}}',good_name='{{$data->name}}')">define</a></td>
                            @if($key%4 === 3) </tr> @endif
                        @endforeach
                    </tbody>
                      <tr>
                          <td colspan="4"></td>
                      </tr>
                      <tr>
                          <td style="text-align: center" colspan="4" class="btn-reddit" onclick="javascript:create()"> plan</td>
                      </tr>
                      <tr>
                          <td style="text-align: center" colspan="4" >计划详情</td>
                      </tr>
                      <tr>
                          <td style="text-align: center" colspan="4" class="append-area"></td>
                      </tr>
                  </table>
                    @else
                    </tbody>
                    </table>
                    <p>没有商品数据！</p>
               @endif
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </section><!-- /.content -->
<input type="hidden" class="plan_guid" value="{{$plan_guid}}">

@endsection
@section('script')
    <script src="{{asset('/dist/js/jquery.js')}}"></script>
    <script src="{{asset('/dist/js/layer.js')}}"></script>
    <script>
//   /*单个商品定义*/
    function define(good_id,good_name) {
        //关联商品的列表
        var goods = "<select id ='rel_good_id'>" +
                @foreach($goods as $v)
                    "<option value ='{{$v->id}}'>{{$v->name}}</option>"+
                @endforeach
            "</select>";

        var html = "<div style='border: 2px solid silver;padding:10px;margin:10px'><p>该商品采购数量:</p>"+
            '<input type="text" id="good_min" class="form-control" style="width:120px;height:30px;display: inline-block" placeholder="最少">'+
            '~'+
            '<input type="text"  id="good_max" class="form-control" style="width:120px;height:30px;display: inline-block" placeholder="最多">' +
            '<br><br>' +
            good_name+'与'+goods+'必须一起购买，比例为:<br><br>' +
            '<input type="text"  id="master_good_num" class="form-control" style="width:120px;height:30px;display: inline-block" placeholder="本商品数量">' +
            "件:" +
            '<input type="text"  id="rel_good_num" class="form-control" style="width:120px;height:30px;display: inline-block" placeholder="关联商品数量">件' +
            "<br><br><a href='javascript:rel_plan("+good_id+")' style='text-align: center'><p class='btn-group'>ok！</p></a>";


        //layer页面层
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['420px', '240px'], //宽高
            content: html
        });
            }

//            生成关联rel_plan表的单个商品的约束
         function rel_plan(good_id) {

            //该商品的购买范围
             var good_count_min = $('#good_min').val();
             var good_count_max = $('#good_max').val();
            //该关联商品的比例
             var master_good_num = $('#master_good_num').val();
             var rel_good_num = $('#rel_good_num').val();
             //该关联商品good_id
             var rel_good_id = $('#rel_good_id').val();
             //该采购计划data_plan的guid
             var plan_guid = $('.plan_guid').val();
//             alert(rel_good_id);
             $.post(
                 "/relPlan/create",
                 {
                     good_count_min:good_count_min,
                     good_count_max:good_count_max,
                     master_good_num:master_good_num,
                     rel_good_num:rel_good_num,
                     master_good_id:good_id,
                     rel_good_id:rel_good_id,
                     plan_guid:plan_guid,
                 },
                 function(result){
                     layer.closeAll();
                     //清空单品弹层数据
                     $('#good_min').attr("value","");
                     $('#good_max').attr("value","");
                     $('#master_good_num').attr("value","");
                     $('#rel_good_num').attr("value","");
                     $('#rel_good_id').attr("value","");

             });

         }

     //   计算出采购计划
      function create(){
//          /*获取预算，商品数量范围*/
          var pay = $('#pay').val();
          var goods_count_min = $('#goods_count_min').val();
          var goods_count_max = $('#goods_count_max').val();
          var plan_guid = $('.plan_guid').val();

//          /*获取选中的商品id*/
            var chk_value =[];
            $('input[name="goods_id"]:checked').each(function(){
                chk_value.push($(this).val());
            });
//            alert(chk_value.length==0 ?'你还没有选择任何商品！':chk_value);

            $.post(
                "/plan",
                {
                    plan_guid:plan_guid,
                    goods_count_min:goods_count_min,
                    goods_count_max:goods_count_max,
                    pay:pay,
                    chk_value:chk_value,
                },
                function(result){
                    var appendHtml = JSON.parse(result);
                    $(".append-area").html(appendHtml);
                });
                    }

    </script>
@endsection