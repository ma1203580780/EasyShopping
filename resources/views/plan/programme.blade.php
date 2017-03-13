@extends('admin.layouts.master')
@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header" >
          <h1>
            <i class="fa fa-calendar"></i>
              方案列表
          </h1>
          <ol class="breadcrumb">
             <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
             <li class="active">方案管理</li>
             <li class="active">方案列表</li>
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
                        <td style="text-align: center" colspan="4">采购方案</td>
                    </tr>

                    <tr>
                        <td colspan="1">方案编号 </td>
                        <td colspan="3">方案内容 </td>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($datas))
                            @foreach($datas as $data)
                                <tr>
                                    <td colspan="1">{{ $data->id }}</td>
                                    <td colspan="3">{{ $data->programme }} </td>
                                </tr>
                                @endforeach
                    </tbody>

                  </table>
                    @else
                    </tbody>
                    </table>
                    <p>没有数据！</p>
               @endif
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </section><!-- /.content -->
@endsection