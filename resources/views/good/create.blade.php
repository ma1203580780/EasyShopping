@extends('admin.layouts.master')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('css/upload_style.css')}}"/>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        @if(empty($new))
            <h1>添加<small></small></h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li><a href="{{url('/language')}}">管理</a></li>
                <li><a href="{{url('/language/create')}}"> 添加</a></li>
            </ol>
        @else
            <h1>修改<small></small></h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li><a href="{{url('/language')}}">管理</a></li>
                <li><a href="{{url('/language/create')}}"> 修改</a></li>
            </ol>
        @endif
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
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="center-block col-md-12">
          <div class="box box-primary">
              <table  class="table table-bordered table-striped table-hover">
                  <tr>
                      <td>
                      <div style="display:none">
                          <form name=theform>
                              <input type="radio" name="myradio" value="local_name"  checked=true/> 上传文件名字保持本地文件名字
                              <input type="radio" name="myradio" value="random_name"  /> 上传文件名字是随机文件名, 后缀保留
                          </form>
                      </div>

                      <h4>视频上传：</h4>
                      <div id="ossfile">你的浏览器不支持flash,Silverlight或者HTML5！因此不能使用该浏览器进行视频文件上传！</div>

                      <br/>

                      <div id="container" style="margin-bottom: 20px;">
                          <a id="selectfiles" href="javascript:void(0);" class='btn'>选择文件</a>
                          <a id="postfiles" href="javascript:void(0);" class='btn'>开始上传</a>
                      </div>

                      <pre id="console"></pre>
                      </td>
                  </tr>
                </table>
				 <div class="box-body">
                       @if(!empty($new))
                             <form action="/blog/{{$new->id}}" method="post" class="form-horizontal" enctype="multipart/form-data" id="bookss" >
                             <input name="_method" type="hidden" value="put"/>
                             <input name="id" type="hidden" value="{{$new->id}}"/>
                       @else
                             <form action="/blog" method="post" class="form-horizontal" enctype="multipart/form-data" id="bookss" >
                       @endif
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <table id="example1" class="table table-bordered table-striped table-hover">
                         <tbody>

                         <tr>
                             <th> 视频</th>
                             <td>
                                 <input name="video_url" type="text" id="videoUrl" class="form-control" readonly style="width:80%" @if(!empty($new))value="{{$new->video_url}}"@endif>
                             </td>
                         </tr>
                         <tr>
                             <th> 标题</th>
                             <td><input name="title" type="text" class="form-control"  style="width:80%" @if(!empty($new))value="{{$new->title}}"@endif></td>
                         </tr>
                         <tr>
                             <th> 分类</th>
                             <td>
                                 <select name="cate_id" style="width:80%" class="form-control" >
                                     @foreach($cate as $temp)
                                     <option value="{{$temp->id}}"  @if(!empty($new))@if($new->cate_id == $temp->id) selected="selected"@endif @endif>{{$temp->cate}}</option>
                                         @endforeach
                                    </select>
                             </td>
                         </tr>
                         <tr>
                             <th> 正文</th>
                             <td>
                                 <script type="text/javascript" charset="utf-8" src="{{asset('ueditor/ueditor.config.js')}}"></script>
                                 <script type="text/javascript" charset="utf-8" src="{{asset('ueditor/ueditor.all.min.js')}}"> </script>
                                 <script id="content" name="content" type="text/plain">
                                     @if(!empty($new))
                                         {!! $new->content !!}
                                     @endif
                                 </script>
                                 <!-- 实例化编辑器 -->
                                 <script type="text/javascript">
                                     UE.getEditor('content',{
                                         initialFrameWidth : 800,
                                         initialFrameHeight : 400,

                                     });
                                     var ue = UE.getEditor('content');
                                 </script>

                             </td>
                         </tr>

                             <tr>

                                 <th>
                                     <button type="submit" style="text-align: center;margin:0 auto;" class="btn btn-block btn-social btn-google btn-flat col-lg-10">保存</button>
                                 </th>

                             </tr>

                         </tbody>
                         <tfoot></tfoot>
                     </table>
                     </form>
                  </div>
			</div>
        </div>
      </div>
    </section>
@endsection

@section('script')
    <script src='{{ asset('js/htmlToOSS/lib/plupload-2.1.2/js/plupload.full.min.js')}}'></script>" +
    "<script src='{{ asset('js/htmlToOSS/upload.js')}}'></script>
    <script>
        $(document).ready(function() {
            $('#push_time').daterangepicker({
                timePicker: false,
                singleDatePicker: true,
                showDropdowns: true,
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection
