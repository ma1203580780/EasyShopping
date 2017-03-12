<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">主要导航</li>
            {{--@if(\Session::get('admin_login')->type == 2)--}}
                {{--@if(Session::get('duties') == 1 || Session::get('duties') == 2)--}}
                    <li class="treeview @if(in_array(\Request::path(),['news','news/create'])) active @endif">
                        <a href="#">
                            <i class="fa fa-edit"></i> <span>人员管理</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('/personnel')}}"><i class="fa fa-circle-o"></i>人员列表</a></li>
                            <li><a href="{{url('/personnel/create')}}"> <i class="fa fa-circle-o"></i>添加人员</a></li>
                        </ul>
                    </li>
                {{--@endif--}}
            {{--@endif--}}
        </ul>
    </section>
</aside>