<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>martist.cn</b>machuang</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="{{ url('/user_center') }}" class="dropdown-toggle">
                        {{--<img src="/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"/>--}}
                        <i class="fa fa-fw fa-user" style="color: #ffffff"></i>
                        <span class="hidden-xs"></span>
                    </a>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="{{ url('/out') }}" class="dropdown-toggle" >
                        <span class="hidden-xs"></span>
                    </a>
                </li>
              {{--  <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>--}}
            </ul>
        </div>
    </nav>
</header>