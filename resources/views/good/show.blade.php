@extends('admin.layouts.master')
@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header" >
          <h1>
            <i class="fa fa-calendar"></i>
			新闻预览
          </h1>
          <ol class="breadcrumb">
             <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
             <li class="active"><a href="/news">新闻管理</a></li>
             <li class="active">新闻预览</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content" >
			<div class="row">
            <div class="col-xs-12">


              <div class="box box-primary">
                <div class="box-body">
                <small>{{$data->cate_name}} — 正文</small>
                    <br>
                    <h2>{{$data->title}}</h2>
                    <hr>
                    <h5>{{date('Y-m-d',$data->push_time)}}</h5> <br>
                    @if(!empty($data->video_url))
                        <div style="text-align: center;margin:0 auto;">
                    <EMBED src="{{$data->video_url}}" width=500px height=400px volume=70 autostart=true></EMBED>
                        </div>
                    @endif
                    {!! $data->content !!}
                    <br><br>
                    @if(!empty($data->picture_url))
                        <hr>
                        <small>新闻封面：</small><br><br>
                        <img src="{{$data->picture_url}}" style="width:400px;height:200px" />
                    @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
            <div style="text-align: center"><a href="/news" class="btn-app" >退出</a></div>
        </section><!-- /.content -->

@endsection
