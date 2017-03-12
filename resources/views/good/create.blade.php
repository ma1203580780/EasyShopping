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

				 <div class="box-body">

                             <form action="/good" method="post" class="form-horizontal" enctype="multipart/form-data" id="bookss" >

                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <table id="example1" class="table table-bordered table-striped table-hover">
                         <tbody>
                         <tr>
                             <th> 商品名称</th>
                             <td>
                                 <input type="text" id="good_name" name="good_name" class="form-control" style="width:80%" >
                             </td>
                         </tr>
                         <tr>
                             <th> 商品价格(单位：元)</th>
                             <td><input name="good_price" type="text" class="form-control"  style="width:80%"></td>
                         </tr>
                         <tr>

                             <td colspan="2"><input  type="submit" class="form-control" value='add' style="width:20%;margin:0 auto"></td>
                         </tr>
                         </tbody>
                     </table>
                     </form>
                  </div>
			</div>
        </div>
      </div>
    </section>
@endsection

