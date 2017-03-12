<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>martist.cn</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    @include('admin.public.style')

    @yield('styles')
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/respond.min.js')  }}"></script>
    <![endif]-->
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">

@include('admin.public.header')
<!-- Left side column. contains the logo and sidebar -->
@include('admin.public.admin-aside')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div><!-- /.content-wrapper -->

@include('admin.public.footer')

<!-- Control Sidebar -->
@include('admin.public.sidebar')<!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->
@include('admin.public.script')
@yield('script')
<div style="display: none">
    <script type="text/javascript">
        var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
        document.write(unescape("%3Cspan id='cnzz_stat_icon_1260740255'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1260740255' type='text/javascript'%3E%3C/script%3E"));
    </script>
</div>
</body>
</html>
