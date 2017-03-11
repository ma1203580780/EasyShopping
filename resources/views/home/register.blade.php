<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
    <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('css/bootstrap-theme.min.css')}}">
    <link rel="stylesheet" href="{{url('css/login.css')}}">
</head>
<body>
<div class="container">
    <div class="main">
        <div class="center">
            <div class="title">注册</div>
            <!-- 登录 -->
            <form action="/register" method="post">
                <div class="form from1" id='form1'>
                    <div class="form-line">
                        <input type="text" class='form-control bg bg-user' placeholder='手机号' name="phone" id="phone" style="color:#000;">
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-line" style='height:60px;'>
                        <input type="text" style='width:160px;padding-left:20px;' class='form-control' placeholder='验证码' name="code" >
                        <a onclick="javascript:phone_captcha();"><p style='float: right;position: absolute;top:0px;right:0;height:45px;line-height: 45px'>获取手机验证码</p></a>

                    </div>

                    <div class="form-line">
                        <input type="password" class='form-control bg  bg-pwd' placeholder='密码' name="password" id="password" style="color:#000">
                        @if (count($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <p class="errorMsg">{{ $error }}</p>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{--<div class="errorMsg">密码错误</div>--}}
                </div>

                <div class="form-line" style='height:60px;'>
                    <button class="btn btn-block " style='background-color:#52a7fa;color:#fff;'  type="submit">注册</button>
                </div>

        </div>
        </form>
    </div>
</div>
</div>
<script src="{{url('js/jquery.js')}}"></script>
<script type="text/javascript">

    function phone_captcha() {
        var url = "{{ URL('/smsauth') }}";
        var phone = $('#phone').val();
        $.post(
            url,
            {
                phone:phone,
                action:4
            },
            function(result){
            var re = JSON.parse(result);
                if(re['ServerNo'] != 0){
                    alert('发送短信失败，请重试！');
                }
        });
    }
</script>
</body>
</html>