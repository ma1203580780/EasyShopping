@extends('admin.layouts.master')
@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header" >
          <h1>
            <i class="fa fa-calendar"></i>
              列表
          </h1>
          <ol class="breadcrumb">
             <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
             <li class="active">管理</li>
             <li class="active">列表</li>
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
                          <td>编号</td>
                          <td>标题</td>
                          <td>板块</td>
                          <td>作者</td>
                          <td>发布时间</td>
                          <td>状态</td>
                          <td style="text-align: center">操作</td>
                      </tr>
                    </thead>
                    <tbody>
                        @if(!empty($datas))
                        @foreach($datas as $data)
                        <tr>
                            <td>{{$data->id}}</td>
                            <td><a href="/news/{{$data->id}}">{{$data->title}}</a></td>
                            <td>{{$data->cate_name}}</td>
                            <td>{{$data->author}}</td>
                            <td>{{date('Y-m-d',$data->addtime)}}</td>
                            <td>@if($data->status == '1') 可用 @else 禁用 @endif</td>
                            <td style="text-align: center">
                                <a href="{{url('/blog/'.$data->id."/edit")}}"><button  class="btn btn-info btn-xs">编辑</button></a>
                                <a href="javascript:upStastus({{$data->id}})">
                                    @if($data->status == '1')
                                        <button  class="btn btn-default btn-xs" id = 'status'>可用</button>
                                    @else
                                        <button  class="btn btn-inverse btn-xs" id = 'status'>禁用</button>
                                    @endif
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                    @else
                    </tbody>
                    </table>
                    <p>没有数据！</p>
               @endif
                    @if(!empty($page))  {!! $page !!} @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@endsection
@section('script')
    <script>
        function upStastus(id) {
            var url = '/blog/status/'+id;
            $.post(
                url,
                {},
                function(result){
                    if(result == 1){
                        $('#status').text('可用');
                    }else{
                        $('#status').text('禁用');
                    }

            });

        }
        $(document).ready(function() {
            $('#start').daterangepicker({
                timePicker: false,
                singleDatePicker: true,
                showDropdowns: true,
                format: 'YYYY-MM-DD'
            });
            $('#end').daterangepicker({
                timePicker: false,
                singleDatePicker: true,
                showDropdowns: true,
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection