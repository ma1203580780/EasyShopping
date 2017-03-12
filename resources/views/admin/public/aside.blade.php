<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">主要导航</li>

            <li class="treeview @if(in_array(\Request::path(),['good','good/create'])) active @endif">
                <a href="/good">
                    <i class="fa fa-edit" ></i> <span>商品管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('/good')}}"><i class="fa fa-circle-o"></i>商品列表</a></li>
                    <li><a href="{{url('/good/create')}}"> <i class="fa fa-circle-o"></i>添加商品</a></li>
                </ul>
            </li>
            <li class="treeview @if(in_array(\Request::path(),['plan','programme'])) active @endif">
                <a href="/plan">
                    <i class="fa fa-edit"></i> <span>方案管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('/plan')}}"><i class="fa fa-circle-o"></i>计划采购</a></li>
                    <li><a href="{{url('/programme')}}"> <i class="fa fa-circle-o"></i>已有方案</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>